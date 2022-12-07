<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $connection = 'auth';

    protected $primaryKey = 'menu_id';
    protected $keyType = 'int';


    public function parent()
    {
        return $this->hasOne('App\Models\Menu', 'menu_id', 'parent_id')->orderBy('sort_order');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id', 'menu_id')->orderBy('sort_order');
    }

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
