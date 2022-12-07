<?php

namespace App\Models;


use App\Models\Role;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Permission extends Eloquent
{
    protected $primaryKey = 'permission_id';
    protected $keyType = 'int';
    protected $connection = 'auth';

	protected $fillable = [
        'name', 'label',
    ];

	public function roles()
    {
        return $this->belongsToMany(Role::class, null, 'permission_id', 'role_id');
    }

    static function permissionsRole($role)
    {
		$permissions_ids = [];

     	foreach ($role->permissions as $permission) {
     		$permissions_ids[] = $permission->permission_id;
     	}

     	return $permissions_ids;
    }

    static function permissionsId($var)
    {
        $permissions_ids = [];

        foreach (Permission::all() as $permission) {
            if($var === 1){
                $permissions_ids[] = $permission->permission_id;
            }
            if($var === 2){
                if($permission->permission_id > 1){
                    $permissions_ids[] = $permission->permission_id;
                }
            }
        }

        return $permissions_ids;
    }

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
