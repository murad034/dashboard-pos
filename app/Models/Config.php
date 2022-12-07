<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Config extends Eloquent
{
    protected $primaryKey = 'config_id';
    protected $keyType = 'int';
    protected $connection = 'mongodb';

    protected $fillable = [
        'app_name', 'app_name_abv', 'app_slogan',
        'captcha', 'datasitekey', 'recaptcha_secret',
        'img_login', 'caminho_img_login', 'tamanho_img_login',
        'titulo_login', 'layout', 'skin', 'favicon',
        'config_id'
    ];

    public static function tableName()
    {
        return with(new static)->getTable();
    }
}
