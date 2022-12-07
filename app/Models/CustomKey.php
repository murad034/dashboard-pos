<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class CustomKey extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $primaryKey = 'customkeyid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
