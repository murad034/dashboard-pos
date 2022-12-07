<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Str;

class RoleUserTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Cria usuários admins (dados controlados)
        $this->createAdmins();

        // Vincula usuários aos papéis
         $this->sync();
    }

    private function createAdmins()
    {
//        User::create([
//            'email' => 'dev@dev.com',
//            'name'  => 'Super Admin',
//            'password' => bcrypt('root'),
//            'avatar'  => 'img/config/nopic.png',
//            'active'  => true,
//            'user_id' => 1,
//            'brand_permissions' => "1",
//            "default_brand" => "1",
//            'mobile' => '61413329187',
//            'member_since' => Carbon::parse('2010-10-12')->format('Y-m-d'),
//            'api_token' => Str::random(60)
//        ]);

        // $this->command->info('User dev created');

        User::create([
            'email' => 'help@ausittechdirect.com.au',
            'name'  => 'Matthew Needham',
            'password' => bcrypt('#Au5T3chGR0up#'),
            'avatar'  => 'https://www.gravatar.com/avatar/81afa9427f15cacaea58ff1263bfcedb?d=http%3A%2F%2Flocalhost%2Fdist%2Fimg%2Fdefault-profile-pic-e1513291410505.jpg&s=300',
            'active'  => true,
            'user_id' => 1,
            'brand_permissions' => "1",
            "default_brand" => "1",
            'mobile' => '61413329187',
            'member_since' => Carbon::parse('2010-10-12')->format('Y-m-d'),
            'api_token' => Str::random(60)
        ]);

        // $this->command->info('Users dev and admin created');
    }

    private function sync()
    {
        $role = User::find(1);
        $role->roles()->sync([1]);

        $this->command->info('Users linked to roles!');
    }
}
