<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class PriceDraft extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

    public static function transformPassedDraftToPricing()
    {
        $ops =  array(

            array(
                '$match' => array(
                    'schedule_at' => array(
                        '$lte' => Carbon::now("UTC")->format("c")
                    )
                )
            )
        );

        $tableData = PriceDraft::raw()->aggregate($ops)->toArray();
        foreach($tableData as $recordData){
            $update_data = array('$set' => array(
                'productprice' => $recordData["productprice"],
                'producttier1' => $recordData["producttier1"],
                'producttier2' => $recordData["producttier2"],
                'producttier3' => $recordData["producttier3"],
                'producttier4' => $recordData["producttier4"],
                'producttier5' => $recordData["producttier5"],
            ));
            $condition = array('sku' => $recordData["sku"], 'storeid' => $recordData["storeid"]);
            Pricing::raw()->updateOne($condition, $update_data, ['upsert' => true]);

            $update_data = array('$set' => array(
                'schedule_at' => null
            ));
            (new static )->raw()->updateOne($condition, $update_data, ['upsert' => true]);

        }
    }
}
