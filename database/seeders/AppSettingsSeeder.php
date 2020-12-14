<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_settings')->delete();
        $appSettings = [
            ['key' => 'date_format', 'value' => 'l jS F Y (H:i:s)'],
            ['key' => 'language', 'value' => 'en'],
            ['key' => 'is_human_date_format', 'value' => '1'],
            ['key' => 'app_name', 'value' => 'STL - Application'],
            ['key' => 'app_short_description', 'value' => 'Small Town Lottery Monitoring Application'],
            ['key' => 'mail_driver', 'value' => 'smtp'],
            ['key' => 'mail_host', 'value' => 'smtp.mailgun.org'],
            ['key' => 'mail_port', 'value' => '587'],
            ['key' => 'app_logo', 'value' => '020a2dd4-4277-425a-b450-426663f52633'],
            ['key' => 'nav_color', 'value' => 'navbar-light bg-white'],
            ['key' => 'logo_bg_color', 'value' => 'bg-white'],
            ['key' => 'default_role', 'value' => 'agent'],
            ['key' => 'google_app_id', 'value' => '527129559488-roolg8aq110p8r1q952fqa9tm06gbloe.apps.googleusercontent.com'],
            ['key' => 'google_app_secret', 'value' => 'FpIi8SLgc69ZWodk-xHaOrxn'],
            ['key' => 'enable_google', 'value' => '1'],
            ['key' => 'default_currency', 'value' => '₱'],
            ['key' => 'fcm_key', 'value' => 'AAAAjAydpBk:APA91bHjcYQ6CVb1UOWwHfjLEj9ZFPF17MqsB0twinInyavAW-XdYf-PexeJH1Rao1ZDTORotHo2rrfWc0HrhWNJMfISvM3B2Ovf4Q-dYbz-8T7t308ckRtu4qjCBveZfzeoA98YYrHd'],
            ['key' => 'enable_notifications', 'value' => '1'],
            ['key' => 'main_color', 'value' => '#25D366'],
            ['key' => 'main_dark_color', 'value' => '#25D366'],
            ['key' => 'second_color', 'value' => '#043832'],
            ['key' => 'second_dark_color', 'value' => '#ccccdd'],
            ['key' => 'google_maps_key', 'value' => 'AIzaSyBIAVYxk9oNd2IKqk6MBzxvKZUsJuioNvk'],
            ['key' => 'mobile_language', 'value' => 'en'],
            ['key' => 'app_version', 'value' => '1.0beta']
        ];
        DB::table('app_settings')->insert($appSettings);
    }
}
