<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Keypad extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';
    protected $primaryKey = 'keypadid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
