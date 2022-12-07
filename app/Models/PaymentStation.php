<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class PaymentStation extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
