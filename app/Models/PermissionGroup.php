<?php

namespace App\Models;

use App\Models\Role;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class PermissionGroup extends Eloquent
{
    protected $primaryKey = 'permission_group_id';
    protected $keyType = 'int';
    protected $connection = 'auth';

	protected $fillable = [
        'name',
    ];

	public function permissions()
    {
    	return $this->hasMany('App\Models\Permission');
    }

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
