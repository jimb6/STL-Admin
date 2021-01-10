<?php

namespace App\Helpers;


use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioSmsHelper
{
    private $config = [];
    private $account_sid;
    private $auth_token;
    private $twilio_number;
    private $countryCode;

    /**
     * TwilioSmsHelper constructor.
     * @param string $account_sid
     * @param string $auth_token
     * @param string $twilio_number
     * @param string $countryCode
     */
    public function __construct($account_sid = '', $auth_token = '', $twilio_number = '', $countryCode = '')
    {
        if ($countryCode==='')  $this->countryCode = config('twilio.country_code'); else $this->countryCode = $countryCode;
        if ($account_sid==='')  $this->account_sid = $account_sid = config('twilio.account_sid'); else $this->account_sid = $account_sid;
        if ($auth_token==='')  $this->account_sid = $auth_token = config('twilio.auth_token'); else $this->auth_token = $auth_token;
        if ($twilio_number==='')  $this->twilio_number = $twilio_number = config('twilio.twilio_number'); else $this->twilio_number = $twilio_number;
    }

    public function sendSms($to, $message)
    {
        try {
            $client = new Client($this->account_sid, $this->auth_token);
            $client->messages->create($this->countryCode.$to,
                ['from' => $this->twilio_number, 'body' => $message]);
        } catch (ConfigurationException $e) {
            return $e->getMessage();
        } catch (TwilioException $e) {
            return $e->getMessage();
        }
        return true;
    }


}
