<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $primaryKey = 'tagid';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
