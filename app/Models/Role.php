<?php

namespace App\Models;

use App\Models\Permission;
use App\Models\User;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Role extends Eloquent
{
    protected $primaryKey = 'role_id';
    protected $keyType = 'int';
    protected $connection = 'auth';
	protected $fillable = [
        'name', 'label',
    ];

    public function permissions()
    {
    	return $this->belongsToMany(Permission::class, null, 'role_id', 'permission_id');
    }

    static function rolesUser($user): array
    {
		$roles_ids = [];

     	foreach ($user->roles as $role) {
     		$roles_ids[] = $role->role_id;
     	}

     	return $roles_ids;
    }

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
