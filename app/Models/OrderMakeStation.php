<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class OrderMakeStation extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
