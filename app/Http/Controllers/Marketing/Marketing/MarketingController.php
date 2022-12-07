<?php

namespace App\Http\Controllers\Marketing\Marketing;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Customer;
use App\Models\DeliveryLog;
use App\Models\EmailTemplate;
use App\Models\MarketingCampaign;
use App\Models\Tag;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketingController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $templateList = EmailTemplate::all();
        $tagList = Tag::all();
        return view('marketing.index', array('templates' => $templateList, 'tags' => $tagList));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $marketing_data = $request->post('data');
            // get max memberid
            $ops = array(

                array(
                    '$group' => array(
                        '_id' => null,
                        'maxid' => array(
                            '$max' => array(
                                '$toDouble' => '$marketingid'
                            )))
                )

            );
            $data = MarketingCampaign::raw()->aggregate($ops)->toArray();
            if (count($data) === 0) {
                $marketing_data["marketingid"] = "1";
            } else {
                $max_id = $data[0]["maxid"];
                $marketing_data["marketingid"] = strval(++$max_id);
            }

            $marketing_data["status"] = "active";
            if ($marketing_data["schedule_available"] === "false"){
                $marketing_data["marketing_status"] = "not submit";
            }
            else{
                $marketing_data["marketing_status"] = "scheduled";
            }

            MarketingCampaign::raw()->insertOne($marketing_data);
            $this->saveLog("saved data : ".json_encode($marketing_data), MarketingCampaign::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->get('id');
                $ops = array(

                    array(
                        '$match' => array('marketingid' => $id)
                    )

                );
                $data = MarketingCampaign::raw()->aggregate($ops)->toArray();
            }
            else if ($request->has('status')){
                if (strcmp($request->get('status'), "all") === 0){
                    $data = MarketingCampaign::all()->toArray();
                }else{
                    $ops = array(
                        array(
                            '$match' => array("status" => $request->get('status'))
                        )
                    );
                    $data = MarketingCampaign::raw()->aggregate($ops)->toArray();
                }

            }
            else {
                $data = MarketingCampaign::all()->toArray();
            }
            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $marketing_data = $request->all();
            if ($marketing_data["schedule_available"] === "false"){
                $marketing_data["marketing_status"] = "not submit";
            }
            else{
                $marketing_data["marketing_status"] = "scheduled";
            }
            $update_data = array('$set' => $marketing_data);
            $condition = array('marketingid' => strval($id));
            MarketingCampaign::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("reference id: " . $id . " updated data : " . json_encode($update_data), MarketingCampaign::tableName());

            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $update_data = array('$set' => array('status' => 'inactive'));
            $condition = array('marketingid' => strval($id));
            MarketingCampaign::raw()->updateOne($condition, $update_data, ['upsert' => true]);
            $this->saveLog("deleted data (reference id): " . $id, MarketingCampaign::tableName());
            return response()->json([
                'status' => 'success'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function checkOpens(Request $request): JsonResponse
    {
        try {

            $SERVER_KEY = env('POSTMARK_SERVER_KEY');
            $API_URL = env('POSTMARK_API_URL');
            $client = new Client();

            $emailList = DeliveryLog::all()->toArray();
            foreach ($emailList as $emailElem){
                $endpoint = $API_URL . "/messages/outbound/opens/".$emailElem["MessageID"]."?count=1&offset=0";
                try {
                    $response = $client->request('GET', $endpoint, [
                        'headers' => [
                            "X-Postmark-Server-Token" => $SERVER_KEY,
                            "Content-Type" => "application/json",
                            "Accept" => "application/json"
                        ],
                    ]);

                    $statusCode = $response->getStatusCode();
                    $content = $response->getBody()->getContents();
                    $responseData = json_decode($content, true);
                    if ($statusCode === 200 && $responseData["TotalCount"] > 0){
                        $update_data = array('$set' => array('Open' => 'yes'));
                        $condition = array('MessageID' => $emailElem["MessageID"]);
                        DeliveryLog::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    }

                }
                catch (ClientException $e){
                    $response = $e->getResponse();
                    $responseBodyAsString = $response->getBody()->getContents();
                    $responseData = json_decode($responseBodyAsString);
                    continue;
                }

            }
            return response()->json([
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLog(Request $request): JsonResponse
    {
        try {
            $data = DeliveryLog::all()->toArray();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function sendEmail(Request $request): JsonResponse
    {
        try {
            $marketId = $request->post('marketingId');
            $marketingData = MarketingCampaign::where('marketingid', '=', $marketId)->get()->toArray();
            if (count($marketingData) === 0){
                return response()->json([
                    'status' => 'fail',
                    'message' => "don't exist marketing data"
                ]);
            }
            else{
                $marketingData = $marketingData[0];
                $templateId = $marketingData["template_id"];
                $tagId = $marketingData["tag_id"];
                $emailTemps = EmailTemplate::where('templateid', '=', $templateId)->get()->toArray();
                if (count($emailTemps) === 0){
                    return response()->json([
                        'status' => 'fail',
                        'message' => "don't exist email template data"
                    ]);
                }else{
                    $tagsData = Tag::where('tagid', '=', $tagId)->get()->toArray();
                    if (count($tagsData) === 0){
                        return response()->json([
                            'status' => 'fail',
                            'message' => "don't exist tag data"
                        ]);
                    }
                    else{
                        $API_KEY = env('POSTMARK_APP_KEY');
                        $SERVER_KEY = env('POSTMARK_SERVER_KEY');
                        $API_URL = env('POSTMARK_API_URL');
                        $client = new Client();

                        $emailTemplateData = $emailTemps[0]["templatedata"];
                        $ops = array(

                            array(
                                '$addFields' => array(
                                    'allocate_tag' => array(
                                        '$split' => array(
                                            '$allocatedtags',
                                            ','
                                        )
                                    )
                                )
                            ),

                            array(
                                '$match' => array(
                                    '$expr' => array(
                                        '$in' => array(
                                            $tagId,
                                            '$allocate_tag'
                                        )
                                    )
                                )
                            )

                        );
                        $customerData = Customer::raw()->aggregate($ops)->toArray();

                        $configData = Config::find(1)->toArray();

                        $endpoint = $API_URL . "/senders/" . $configData["signature_id"];
                        try {
                            $response = $client->request('GET', $endpoint, [
                                'headers' => [
                                    "X-Postmark-Account-Token" => $API_KEY,
                                    "Content-Type" => "application/json",
                                    "Accept" => "application/json"
                                ],
                            ]);


                            $statusCode = $response->getStatusCode();
                            $content = $response->getBody()->getContents();
                            $responseData = json_decode($content);

                            //                       send bulk email postmark

                            $endpoint = $API_URL . "/email/batch";
                            $customerArr = array_chunk($customerData, 500);
                            foreach ($customerArr as $customerList){
                                $postData = array();
                                foreach ($customerList as $customer){
                                    $element = array();
                                    $element["From"] = $configData["send_mail"];
                                    $element["To"] = $customer["email"];
                                    $element["Subject"] = "Test";
                                    $element["TextBody"] = "Test";
                                    $element["HtmlBody"] = $emailTemplateData;
                                    $element["MessageStream"] = "outbound";
                                    array_push($postData, $element);
                                }
                                try {
                                    $response = $client->request('POST', $endpoint, [
                                        'headers' => [
                                            "X-Postmark-Server-Token" => $SERVER_KEY,
                                            "Content-Type" => "application/json",
                                            "Accept" => "application/json"
                                        ],
                                        'body' => json_encode($postData),
                                    ]);


                                    $statusCode = $response->getStatusCode();
                                    $content = $response->getBody()->getContents();
                                    $responseData = json_decode($content, true);
                                    foreach ($responseData as $resData){
                                        $resData = array_merge($resData, $marketingData);
                                        unset($resData["_id"]);
                                        $resData["From"] = $configData["send_mail"];
                                        $resData["Open"] = "no";
                                        DeliveryLog::raw()->insertOne($resData);
                                    }

                                }
                                catch (ClientException $e){
                                    $response = $e->getResponse();
                                    $responseBodyAsString = $response->getBody()->getContents();
                                    $responseData = json_decode($responseBodyAsString);
                                    return response()->json([
                                        'status' => 'fail',
                                        'message' => $responseData->Message,
                                        'errorCode' => "error : " . $responseData->ErrorCode
                                    ]);
                                }
                            }
                            $update_data = array(array('$set' => array("schedule_at" => null, "schedule_available" => "false", "marketing_status" => "submitted")));
                            $condition = array('marketingid' => strval($marketId));
                            MarketingCampaign::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                            $this->saveLog("send bulk campaign email: " . $marketId, MarketingCampaign::tableName());
                            return response()->json([
                                'status' => 'success'
                            ]);

                        } catch (ClientException $e) {
                            $response = $e->getResponse();
                            $responseBodyAsString = $response->getBody()->getContents();
                            $responseData = json_decode($responseBodyAsString);
                            return response()->json([
                                'status' => 'fail',
                                'message' => $responseData->Message,
                                'errorCode' => "error : " . $responseData->ErrorCode
                            ]);
                        }


                    }
                }

            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }
    }
}
