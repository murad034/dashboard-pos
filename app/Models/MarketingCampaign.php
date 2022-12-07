<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class MarketingCampaign extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $primaryKey = 'marketingid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

    public static function sendBulkEmailSchedule(): \Illuminate\Http\JsonResponse
    {
        $marketingList = MarketingCampaign::where('schedule_at', '<=', Carbon::now("UTC")->format("c"))
            ->where('schedule_available', '=', 'true')
            ->get()->toArray();


        try {
            foreach ($marketingList as $marketingData) {
                $marketingId = $marketingData["marketingid"];
                $templateId = $marketingData["template_id"];
                $tagId = $marketingData["tag_id"];
                $emailTemps = EmailTemplate::where('templateid', '=', $templateId)->get()->toArray();
                if (count($emailTemps) === 0) {
                    return response()->json([
                        'status' => 'fail',
                        'message' => "don't exist email template data"
                    ]);
                } else {
                    $tagsData = Tag::where('tagid', '=', $tagId)->get()->toArray();
                    if (count($tagsData) === 0) {
                        return response()->json([
                            'status' => 'fail',
                            'message' => "don't exist tag data"
                        ]);
                    } else {
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
                            foreach ($customerArr as $customerList) {
                                $postData = array();
                                foreach ($customerList as $customer) {
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
                                    foreach ($responseData as $resData) {
                                        $resData = array_merge($resData, $marketingData);
                                        unset($resData["_id"]);
                                        $resData["From"] = $configData["send_mail"];
                                        $resData["Open"] = "no";
                                        DeliveryLog::raw()->insertOne($resData);
                                    }

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

                        $condition = array('marketingid' => $marketingId);
                        $update_data = array(array('$set' => array("schedule_at" => null, "schedule_available" => "false", "marketing_status" => "submitted")));
                        (new static )->raw()->updateOne($condition, $update_data);
                    }
                }
            }

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
}
