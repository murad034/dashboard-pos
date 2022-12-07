<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Config;


class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            'app_name' => 'IMREKE Point DashBoard', // String
            'app_name_abv'  => 'POS', // String
            'captcha'  => 'F', // 'T' or 'F'
            'datasitekey'  => '', //String
            'recaptcha_secret'  => '', //String
            'logo_background' => 'img/config/logo_background.png',
            'logo_internal' => 'img/config/logo_internal.png',
            'titulo_login'  => '<a href="#" ><b>IMREKE</b> Point DashBoard</a>', //String
            'layout'  => 'fixed', //String -> defaut: 'fixed'
            'skin'  => 'blue', //String -> defaut: 'blue'
            'favicon'  => 'img/config/favicon.png', //String,
            'config_id' => 1,
            'wp_url' => 'https://en2vtpm0fvwtyux.m.pipedream.net/',
            'wp_token' => '',
            'logo_icon' => 'img/config/logo_icon.png',
            'load_gif' => 'img/animation_500_l1r5m0bj.gif',
            'send_mail' => '',
            'timezone' => '',
            'hold_bsb' => '',
            'hold_account_number' => '',
            'hold_payment_fee' => ''
        ]);
    }
}
