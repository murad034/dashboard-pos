<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['id' => '1',   'value' => 'DailySalesSummary',         'title' => 'Daily Sales Summary'],
            ['id' => '2',   'value' => 'DetailSaleStore',           'title' => 'Detailed Sales Transaction'],
            ['id' => '3',   'value' => 'MemberSaleSummary',         'title' => 'Member Sales Summary'],
            ['id' => '4',   'value' => 'NationalSaleRanking',       'title' => 'National Sales Rankings by Store'],
            ['id' => '5',   'value' => 'SalesSummaryByStaff',       'title' => 'Clerk Sale, Clear and No Sale Summary'],
            ['id' => '6',   'value' => 'StoreSalesSummary',         'title' => 'Sales Summary by Store'],
            ['id' => '7',   'value' => 'DailySalesSummaryByHour',   'title' => 'Daily Sales Summary By Hour'],
            ['id' => '8',   'value' => 'ProductGroupAndCategory',   'title' => 'Product Group and Category'],
            ['id' => '9',   'value' => 'AgingStock',                'title' => 'Aging Stock'],
        ];

        foreach ($menus as $menu) {
            Report::Create($menu);
        }
    }
}
