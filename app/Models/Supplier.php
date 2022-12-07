<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $connection = 'mongodb';

    protected $primaryKey = 'supplier_id';

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
