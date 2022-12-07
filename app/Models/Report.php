<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $connection = 'auth';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
