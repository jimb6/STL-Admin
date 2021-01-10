<?php


namespace App\Http\Controllers\Auth;


use App\Helpers\SendsPasswordResetOtp;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class OtpPasswordController
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset mobile and
        | includes a trait which assists in sending these notifications from
        | your application to your users. Feel free to explore this trait.
        |
        */

    use SendsPasswordResetOtp;
}
