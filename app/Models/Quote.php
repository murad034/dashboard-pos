<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $primaryKey = 'quote_id';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

}
