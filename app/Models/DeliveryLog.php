<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class DeliveryLog extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

    public static function checkOpens(){
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
                        (new static )->raw()->updateOne($condition, $update_data, ['upsert' => true]);
                    }

                }
                catch (ClientException $e){
                    $response = $e->getResponse();
                    $responseBodyAsString = $response->getBody()->getContents();
                    $responseData = json_decode($responseBodyAsString);
                    continue;
                }

            }
        } catch (Exception $e) {

        }
    }
}
