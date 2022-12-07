<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'user_id';
    protected $keyType = 'int';
    protected $connection = 'mongodb';

    use Notifiable;

    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'active', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, null, "user_id", "role_id");
    }

    public function hasPermission(Permission $permission)
    {
        return $this->hasAnyRoles($permission->roles);
    }

    public function hasAnyRoles($roles)
    {
        $result = $roles->count();
        if(is_array($roles) || is_object($roles) ) {
            return !! $roles->intersect($this->roles)->count();
//            return 1;
        }

        return $this->roles->contains('name', $roles);
    }

    public function isOnline(): bool
    {
        return Cache::has('user-is-online-' . $this->user_id);
    }


    public function findBrands()
    {
        $brandPermissions = explode(",", trim($this->brand_permissions));
        $ops = array(

            array(
                '$match' => array("brandid"=> array('$in' => $brandPermissions))
            )

        );
        return Brand::raw()->aggregate($ops);
    }

    public static function getLastId(): int
    {
        return (int)User::orderBy('user_id', 'desc')->first()->user_id + 1;
    }


    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
