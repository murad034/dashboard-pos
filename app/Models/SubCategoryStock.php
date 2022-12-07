<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class SubCategoryStock extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $primaryKey = 'subcatid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
