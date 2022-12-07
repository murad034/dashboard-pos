<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['menu_id' => 1,    'menu_title' => 'Dashboard',                        'parent_id' => 0,   'sort_order' => 0,  'icon' => 'fa fa-gauge',            'slug' => '/home',                      'segment' => ['home']],
            ['menu_id' => 23,   'menu_title' => 'Point of Sale',                     'parent_id' => 0,   'sort_order' => 0, 'icon' => 'fa fa-file-invoice',     'slug' => '/pos',                    'segment' => ['pos']],
            ['menu_id' => 2,    'menu_title' => 'Products',                         'parent_id' => 0,   'sort_order' => 1,  'icon' => 'fa fa-barcode',          'slug' => '#',                          'segment' => ['products', 'product-categories', 'bulk-edit']],
            ['menu_id' => 3,    'menu_title' => 'Product List',                     'parent_id' => 2,   'sort_order' => 2,  'icon' => 'fa fa-clipboard-list',   'slug' => '/products',                  'segment' => ['products']],
            ['menu_id' => 4,    'menu_title' => 'Categories & Sub Categories',      'parent_id' => 2,   'sort_order' => 3,  'icon' => 'fa fa-clipboard-list',   'slug' => '/product-categories',        'segment' => ['product-categories']],
            ['menu_id' => 5,    'menu_title' => 'Bulk Pricing Edit',                'parent_id' => 2,   'sort_order' => 3,  'icon' => 'fa fa-clipboard-list',   'slug' => '/bulk-edit',                 'segment' => ['bulk-edit',]],
            ['menu_id' => 6,    'menu_title' => 'Stock',                            'parent_id' => 0,   'sort_order' => 4,  'icon' => 'fa fa-barcode',          'slug' => '#',                          'segment' => ['stocks', 'stock-categories', 'purchase', 'receive-stock', 'stock-take']],
            ['menu_id' => 7,    'menu_title' => 'Stock List',                       'parent_id' => 6,   'sort_order' => 5,  'icon' => 'fa fa-clipboard-list',   'slug' => '/stocks',                    'segment' => ['stocks']],
            ['menu_id' => 8,    'menu_title' => 'Categories & Sub Categories',      'parent_id' => 6,   'sort_order' => 5,  'icon' => 'fa fa-clipboard-list',   'slug' => '/stock-categories',          'segment' => ['stock-categories']],
            ['menu_id' => 9,    'menu_title' => 'Purchase Orders',                  'parent_id' => 6,   'sort_order' => 5,  'icon' => 'fa fa-bag-shopping',     'slug' => '/purchase',                  'segment' => ['purchase']],
            ['menu_id' => 10,   'menu_title' => 'Receive Stock',                    'parent_id' => 6,   'sort_order' => 5,  'icon' => 'fa fa-truck-loading',    'slug' => '/receive-stock',             'segment' => ['receive-stock']],
            ['menu_id' => 11,   'menu_title' => 'Stock Take',                       'parent_id' => 6,   'sort_order' => 5,  'icon' => 'fa fa-check-circle',     'slug' => '/stock-take',                'segment' => ['stock-take']],
            ['menu_id' => 12,   'menu_title' => 'Designers',                        'parent_id' => 0,   'sort_order' => 6,  'icon' => 'fa fa-pen',              'slug' => '#',                          'segment' => ['order-keypad-designer', 'media-board-designer', 'customer-receipt-designer', 'email-marketing-designer']],
            ['menu_id' => 13,   'menu_title' => 'Order Keypad Designer(OKD)',       'parent_id' => 12,  'sort_order' => 7,  'icon' => 'fa fa-keyboard',         'slug' => '/order-keypad-designer',     'segment' => ['order-keypad-designer']],
            ['menu_id' => 14,   'menu_title' => 'Media Board Designer(MBD)',        'parent_id' => 12,  'sort_order' => 7,  'icon' => 'fa fa-photo-video',      'slug' => '/media-board-designer',      'segment' => ['media-board-designer']],
            ['menu_id' => 15,   'menu_title' => 'Customer Receipt Designer(CRD)',   'parent_id' => 12,  'sort_order' => 7,  'icon' => 'fa fa-receipt',          'slug' => '/customer-receipt-designer', 'segment' => ['customer-receipt-designer']],
            ['menu_id' => 16,   'menu_title' => 'Email Marketing Designer(EMD)',    'parent_id' => 12,  'sort_order' => 7,  'icon' => 'fa fa-receipt',          'slug' => '/email-marketing-designer',  'segment' => ['email-marketing-designer']],
            ['menu_id' => 17,   'menu_title' => 'Stations',                         'parent_id' => 0,   'sort_order' => 8,  'icon' => 'fa fa-server',           'slug' => '#',                          'segment' => ['order-payment-station', 'order-make-station', 'media-display-station']],
            ['menu_id' => 18,   'menu_title' => 'Order Payment Station(OPS)',       'parent_id' => 17,  'sort_order' => 9,  'icon' => 'fa fa-tv',               'slug' => '/order-payment-station',     'segment' => ['order-payment-station']],
            ['menu_id' => 19,   'menu_title' => 'Order Make Station(OMS)',          'parent_id' => 17,  'sort_order' => 9,  'icon' => 'fa fa-tv',               'slug' => '/order-make-station',        'segment' => ['order-make-station']],
            ['menu_id' => 20,   'menu_title' => 'Media Display Station(MDS)',       'parent_id' => 17,  'sort_order' => 9,  'icon' => 'fa fa-tv',               'slug' => '/media-display-station',     'segment' => ['media-display-station']],
            ['menu_id' => 21,   'menu_title' => 'Custom Keypad Keys',               'parent_id' => 0,   'sort_order' => 10, 'icon' => 'fa fa-keyboard',         'slug' => '/custom-keypad-keys',        'segment' => ['custom-keypad-keys']],
            ['menu_id' => 22,   'menu_title' => 'Promos',                           'parent_id' => 0,   'sort_order' => 11, 'icon' => 'fa fa-trophy',           'slug' => '/promos',                    'segment' => ['promos']],
            ['menu_id' => 24,   'menu_title' => 'Profiles',                        'parent_id' => 0,   'sort_order' => 12, 'icon' => 'fa fa-user-friends',     'slug' => '/customers',                 'segment' => ['customers']],
            ['menu_id' => 25,   'menu_title' => 'Marketing',                        'parent_id' => 0,   'sort_order' => 13, 'icon' => 'fa fa-id-badge',         'slug' => '#',                          'segment' => ['marketing', 'tags', 'automation']],
            ['menu_id' => 26,   'menu_title' => 'Campaigns',                        'parent_id' => 25,  'sort_order' => 13, 'icon' => 'fa fa-mail-bulk',        'slug' => '/marketing',                 'segment' => ['marketing']],
            ['menu_id' => 27,   'menu_title' => 'Tags',                             'parent_id' => 25,  'sort_order' => 13, 'icon' => 'fa fa-tag',              'slug' => '/tags',                      'segment' => ['tags']],
            ['menu_id' => 28,   'menu_title' => 'Automation',                       'parent_id' => 25,  'sort_order' => 14, 'icon' => 'fa fa-cloud',            'slug' => '/automation',                'segment' => ['automation']],
            ['menu_id' => 29,   'menu_title' => 'Rosters',                          'parent_id' => 0,   'sort_order' => 15, 'icon' => 'fa fa-clock',            'slug' => '/rosters',                   'segment' => ['rosters']],
            ['menu_id' => 30,   'menu_title' => 'Reports',                          'parent_id' => 0,   'sort_order' => 16, 'icon' => 'fa fa-chart-line',       'slug' => '/reports',                   'segment' => ['reports']],
            ['menu_id' => 31,   'menu_title' => 'Locations',                        'parent_id' => 0,   'sort_order' => 17, 'icon' => 'fa fa-location-arrow',   'slug' => '/locations',                 'segment' => ['locations']],
            ['menu_id' => 32,   'menu_title' => 'Suppliers',                        'parent_id' => 0,   'sort_order' => 18, 'icon' => 'fa fa-truck',            'slug' => '/suppliers',                 'segment' => ['suppliers']],
            ['menu_id' => 33,   'menu_title' => 'Staff',                            'parent_id' => 0,   'sort_order' => 19, 'icon' => 'fa fa-users',            'slug' => '/staff',                     'segment' => ['staff']],
            ['menu_id' => 34,   'menu_title' => 'Settings',                         'parent_id' => 0,   'sort_order' => 20, 'icon' => 'fa fa-cog',              'slug' => '#',                          'segment' => ['config', 'user', 'profile', 'global', 'logs']],
            ['menu_id' => 35,   'menu_title' => 'SettingAPP',                       'parent_id' => 34,  'sort_order' => 21, 'icon' => 'fa fa-cog',              'slug' => '/config',                    'segment' => ['config']],
            ['menu_id' => 36,   'menu_title' => 'Users',                            'parent_id' => 34,  'sort_order' => 21, 'icon' => 'fa fa-user',             'slug' => '/user',                      'segment' => ['user']],
            ['menu_id' => 37,   'menu_title' => 'PROFILE',                          'parent_id' => 34,  'sort_order' => 21, 'icon' => 'fa fa-user',             'slug' => '/profile',                   'segment' => ['profile']],
            ['menu_id' => 38,   'menu_title' => 'Logs',                             'parent_id' => 34,  'sort_order' => 21, 'icon' => 'fa fa-book',             'slug' => '/logs',                      'segment' => ['logs']],

        ];

        foreach ($menus as $menu) {
            Menu::Create($menu);
        }
    }
}
