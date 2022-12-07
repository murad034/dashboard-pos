<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $connection = 'auth';

    protected $fillable = ['brandname', 'brandident', 'brandid'];
    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
