<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    //

    public function globalSettings()
    {
        return view('settings.app.global');
    }

    public function mobileSettings()
    {
        return view('settings.mobile.global');
    }
}
