<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'user_name', 'action', 'table'];

    protected $connection = 'mongodb';

//    protected $primaryKey = 'log_id';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}

