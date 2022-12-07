<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\ConfigRequest;
use App\Models\Config;
use App\Models\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\ClientException;

class ConfigController extends Controller
{

    public function index()
    {
        $config = Config::find(1);

        $this->authorize('root-dev', $config);

        return view('config.index', compact('config'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Request $request)
    {
        $this->authorize('root-dev', Config::class);
        $data = $request->all();
        $data["config_id"] = (int)$data["config_id"];

        $update_data = array('$set' => $data);
        $condition = array('config_id' => (int)$data["config_id"]);
        Config::raw()->updateOne($condition, $update_data, ['upsert' => true]);

        $this->saveLog("reference id: " . $data["config_id"] . " updated data : " . json_encode($update_data), Config::tableName());

//        Config::find((int)$id)->update($request->all(), ['upsert' => true]);

        if ($request->file('logo_background')) {
            $file = $request->file('logo_background');
            $ext = $file->guessClientExtension();
            $path = $file->move("img/config", "logo_background.{$ext}");
            Config::where('config_id', 1)->update(['logo_background' => "img/config/logo_background.{$ext}"], ['upsert' => true]);

            $this->saveLog("updated logo background", Config::tableName());

        }

        if ($request->file('logo_internal')) {
            $file = $request->file('logo_internal');
            $ext = $file->guessClientExtension();
            $path = $file->move("img/config", "logo_internal.{$ext}");
            Config::where('config_id', 1)->update(['logo_internal' => "img/config/logo_internal.{$ext}"], ['upsert' => true]);
            $this->saveLog("updated logo internal image", Config::tableName());
        }

        if ($request->file('favicon')) {
            $file = $request->file('favicon');
            $ext = $file->guessClientExtension();
            $path = $file->move("img/config", "favicon.{$ext}");
            Config::where('config_id', 1)->update(['favicon' => "img/config/favicon.{$ext}"], ['upsert' => true]);
            $this->saveLog("updated favicon", Config::tableName());
        }

        if ($request->file('logo_icon')) {
            $file = $request->file('logo_icon');
            $ext = $file->guessClientExtension();
            $path = $file->move("img/config", "logo_icon.{$ext}");
            Config::where('config_id', 1)->update(['logo_icon' => "img/config/logo_icon.{$ext}"], ['upsert' => true]);
            $this->saveLog("updated logo icon", Config::tableName());
        }

        $this->flashMessage('check', 'Application settings updated successfully!', 'success');


        return response()->json([
            'status' => 'success',
        ]);
    }

    /**
     * verify mail to postmark
     *
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function mailConfirm(){
        try {
            $API_KEY = env('POSTMARK_APP_KEY');
            $API_URL = env('POSTMARK_API_URL');
            $configData = Config::find(1)->toArray();
            if (strcmp($configData["signature_id"], "") !== 0){
                $endpoint = $API_URL . "/senders/" . $configData["signature_id"];
                $client = new \GuzzleHttp\Client();
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

                    if($responseData->Confirmed === true){
                        return response()->json([
                            'status' => 'success',
                            'action' => 'delete'
                        ]);
                    }
                    else{
                        return response()->json([
                            'status' => 'success',
                            'action' => 'confirm'
                        ]);
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
            else{
                return response()->json([
                    'status' => 'fail',
                    'message' => "not exist signature id in table",
                    'errorCode' => 'error : 404'
                ]);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage(),
                'errorCode' => 'error'
            ]);
        }
    }

    /**
     * verify mail to postmark
     *
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function verifyMail(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->has('action')) {
                $API_KEY = env('POSTMARK_APP_KEY');
                $API_URL = env('POSTMARK_API_URL');
                $action = $request->post('action');

                switch ($action) {
//                    verify email, post mail verify to postmark and save signature id to table.
                    case "Verify":
                        if ($request->has('mail') && $request->has('name')) {
                            $mail = $request->post('mail');
                            $appName = $request->post('name');
                            $endpoint = $API_URL . "/senders";
                            $client = new \GuzzleHttp\Client();
                            try {
                                $response = $client->request('POST', $endpoint, [
                                    'headers' => [
                                        "X-Postmark-Account-Token" => $API_KEY,
                                        "Content-Type" => "application/json",
                                        "Accept" => "application/json"
                                    ],
                                    'body' => json_encode([
                                        'FromEmail' => $mail,
                                        'Name' => $appName,
                                        'ConfirmationPersonalNote' => "Hi you have received this email as you have added your sending email address in your IMReKe POS dashboard. We use Postmark to deliver all email and provide you with the best possible chance of delivery to your customers inboxes. Please confirm your email address by clicking the Confirm Sender Signature button below. If you have any questions please call our support team on 1800 314 415"
                                    ]),
                                ]);


                                $statusCode = $response->getStatusCode();
                                $content = $response->getBody()->getContents();
                                $responseData = json_decode($content);


                                $update_data = array('$set' => array('signature_id' => $responseData->ID, 'send_mail' => $mail));
                                $condition = array('config_id' => 1);
                                Config::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                                $this->saveLog("reference id: " . 1 . " updated data : " . json_encode($update_data), Config::tableName());
                                return response()->json([
                                    'status' => 'success',
                                    'data' => $responseData
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


                        } else {
                            return response()->json([
                                'status' => 'fail',
                                'message' => "missed parameters",
                                'errorCode' => "error : 404"
                            ]);
                        }
                        break;

//                       confirm email
                    case "Confirm":
                        $configData = Config::find(1)->toArray();
                        $endpoint = $API_URL . "/senders/" . $configData["signature_id"];
                        $client = new \GuzzleHttp\Client();
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

                            if($responseData->Confirmed === true){
                                return response()->json([
                                    'status' => 'success',
                                    'data' => $responseData
                                ]);
                            }
                            else{
                                return response()->json([
                                    'status' => 'fail',
                                    'message' => "not confirmed",
                                    'errorCode' => "error"
                                ]);
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
                        break;
//                        delete email and signature
                    case "Delete":
                        $configData = Config::find(1)->toArray();
                        $endpoint = $API_URL . "/senders/" . $configData["signature_id"];
                        $client = new \GuzzleHttp\Client();
                        try {
                            $response = $client->request('DELETE', $endpoint, [
                                'headers' => [
                                    "X-Postmark-Account-Token" => $API_KEY,
                                    "Content-Type" => "application/json",
                                    "Accept" => "application/json"
                                ],
                            ]);


                            $statusCode = $response->getStatusCode();
                            $content = $response->getBody()->getContents();
                            $responseData = json_decode($content);

                            $update_data = array('$set' => array('signature_id' => "", 'send_mail' => ""));
                            $condition = array('config_id' => 1);
                            Config::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                            $this->saveLog("deleted send mail and signature id", Config::tableName());

                            return response()->json([
                                'status' => 'success',
                                'data' => $responseData
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
                        break;
                    case "Resend":
                        $configData = Config::find(1)->toArray();
                        $endpoint = $API_URL . "/senders/" . $configData["signature_id"]. "/resend";
                        $client = new \GuzzleHttp\Client();
                        try {
                            $response = $client->request('POST', $endpoint, [
                                'headers' => [
                                    "X-Postmark-Account-Token" => $API_KEY,
                                    "Content-Type" => "application/json",
                                    "Accept" => "application/json"
                                ],
                            ]);


                            $statusCode = $response->getStatusCode();
                            $content = $response->getBody()->getContents();
                            $responseData = json_decode($content);

                            return response()->json([
                                'status' => 'success',
                                'data' => $responseData
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
                        break;

                    default:
                        break;

                }
                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'update token failed'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * update the token function.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateToken(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->has('id')) {
                $id = $request->post('id');

                $token = Str::random(60);
                $update_data = array('$set' => array('api_token' => $token));
                $condition = array('user_id' => (int)$id);
                User::raw()->updateOne($condition, $update_data, ['upsert' => true]);

                $this->saveLog(" updated token : " . json_encode($update_data), User::tableName());
                return response()->json([
                    'status' => 'success',
                    'data' => $token
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'update token failed'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }

    /**
     * update the token function.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSignature(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            if ($request->has('id')) {
                $signature = $request->post('signature');
                $sendMail = $request->post('mail');
                $id = $request->post('id');

                $update_data = array('$set' => array('signature_id' => $signature, 'send_mail' => $sendMail));
                $condition = array('config_id' => (int)$id);
                Config::raw()->updateOne($condition, $update_data, ['upsert' => true]);
                $this->saveLog(" updated signature : " . json_encode($update_data), User::tableName());
                return response()->json([
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'update signature failed'
                ]);
            }


        } catch (Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ]);
        }

    }
}
