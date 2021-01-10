<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Twilio Custom Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration made by Jim. for sms
    |
    */

    'country_code' => env('TWILIO_COUNTRY_CODE', '+63'),

    'account_sid' => env('TWILIO_SID'),
    'auth_token' => env('TWILIO_AUTH_TOKEN'),
    'twilio_number' => env('TWILIO_NUMBER'),


];
