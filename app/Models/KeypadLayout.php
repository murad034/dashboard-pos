<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class KeypadLayout extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    public static function tableName()
    {
        return with(new static)->getTable();
    }

    public static function transformPassedDraftToLiveRecord()
    {

        $condition = array('scheduleAt' => array('$lte' =>
            Carbon::now("UTC")->format("c")), 'isdraft' => true);
        $update_data = array(array('$set' => array("scheduleAt" => null, "isdraft" => false, "jsonpath" => '$draft')));
        (new static )->raw()->updateMany($condition, $update_data);
        return;
    }
}
