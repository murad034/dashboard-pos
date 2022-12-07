<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['brandid' => '1',   'brandident' => 'test',      'brandname' => 'test'],
        ];

        foreach ($menus as $menu) {
            Brand::Create($menu);
        }
    }
}
