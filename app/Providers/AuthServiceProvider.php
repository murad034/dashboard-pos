<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return array
     */
    public function boot()
    {
        $this->registerPolicies();

        try {
            $permissions = Permission::with('roles')->get();
        } catch (\Exception $e) {
            return [];
        }

        foreach ($permissions as $permission) {
            Gate::define($permission->name, function ($user) use ($permission){
                return $user->hasPermission($permission);
            });
        }
    }
}
