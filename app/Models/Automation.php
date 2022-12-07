<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Automation extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $primaryKey = 'automationid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

}
