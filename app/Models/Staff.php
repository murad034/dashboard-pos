<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $primaryKey = 'staffid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
