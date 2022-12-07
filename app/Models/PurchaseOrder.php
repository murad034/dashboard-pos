<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $primaryKey = 'purchase_id';

    public static function tableName()
    {
        return with(new static)->getTable();
    }

}
