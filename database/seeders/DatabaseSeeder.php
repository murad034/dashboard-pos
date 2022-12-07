<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	Model::unguard();

//    	 DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    	$this->command->info('Initializing...');
    	$this->command->info('Deleting tables...');

    	Config::truncate();

        DB::connection('auth')->table('permissions')->truncate();
        DB::connection('auth')->table('permission_groups')->truncate();
        DB::connection('auth')->table('roles')->truncate();
        User::truncate();
        DB::connection('auth')->table('menus')->truncate();
        DB::connection('auth')->table('reports')->truncate();
        DB::connection('auth')->table('brands')->truncate();

        $this->command->info('Deleted tables!');
        $this->command->info('Creating Tables...');

        $this->call([
            ConfigTableSeeder::class,
            PermissionRoleTablesSeeder::class,
            RoleUserTablesSeeder::class,
            MenuSeeder::class,
            ReportSeeder::class,
            BrandSeeder::class,
        ]);

        $this->command->info('Finished!');

//    	 DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
