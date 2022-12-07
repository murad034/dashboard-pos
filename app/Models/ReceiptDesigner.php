<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

//use Neelkanth\Laravel\Schedulable\Traits\Schedulable;

class ReceiptDesigner extends Model
{
    use HasFactory;
//    use Schedulable;

    protected $connection = 'mongodb';

    protected $primaryKey = 'templateid';

    // const SCHEDULE_AT = "publish_at"; //Specify the custom column name

    public static function tableName()
    {
        return with(new static )->getTable();
    }

    public static function saveLive($template_data)
    {
        $template_data["data"]["isdraft"] = false;
        $template_data["data"]["scheduleAt"] = null;
        $update_data = array('$set' => $template_data["data"]);
        $condition = array('templateid' => strval($template_data["id"]));
        return with(new static )->raw()->updateOne($condition, $update_data, ['upsert' => true]);
    }
    public static function saveDraft($template_data)
    {
        $template_data["data"]["isdraft"] = false;
        $template_data["data"]["scheduleAt"] = null;
        $update_data = array('$set' => $template_data["data"]);
        $condition = array('templateid' => strval($template_data["id"]));
        return with(new static )->raw()->updateOne($condition, $update_data, ['upsert' => true]);
    }

    public static function saveDraftWithScheduleAt($template_data)
    {
        $template_data["data"]["isdraft"] = true;
        $template_data["data"]["scheduleAt"] = $template_data['scheduleAt'];
        $update_data = array('$set' => $template_data["data"]);
        $condition = array('templateid' => strval($template_data["id"]));
        return with(new static )->raw()->updateOne($condition, $update_data, ['upsert' => true]);
    }

    public static function transformPassedDraftToLiveRecord()
    {

        $condition = array('scheduleAt' => array('$lte' =>
            Carbon::now("UTC")->format("c")), 'isdraft' => true);
        $update_data = array(array('$set' => array("scheduleAt" => null, "isdraft" => false, "rectemplate" => '$draft')));
        (new static )->raw()->updateMany($condition, $update_data);
        return;
    }
}
