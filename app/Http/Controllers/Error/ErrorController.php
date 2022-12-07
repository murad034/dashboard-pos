<?php

namespace App\Http\Controllers\Error;

use App\Http\Controllers\Controller;

class ErrorController extends Controller
{


    public function unauthorized()
    {
        return view('errors.unauthorized');
    }
}
