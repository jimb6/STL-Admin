<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use Illuminate\Http\Request;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SMSController extends Controller
{

    public function sendMessage(Request $request)
    {

        $validated = $request->validate([
            'message' => 'required',
            'send_to' => 'required'
        ]);
        $appSettings = AppSettings::all();
        $account_sid = $appSettings->where('key','=','twilio_sid')->values()[0]->value;
        $auth_token = $appSettings->where('key', '=', 'twilio_token')->values()[0]->value;
        $twilio_number = $appSettings->where('key', '=', 'twilio_number')->values()[0]->value;
        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create('+63'.$validated['send_to'],
                ['from' => $twilio_number, 'body' => $validated['message']] );
        } catch (ConfigurationException $e) {
            return response($e, 400);
        } catch (TwilioException $e) {
            return response($e, 400);
        }
        return response([$account_sid, $auth_token, $twilio_number, $validated], 200);

    }


    public function sendCustomMessage(Request $request)
    {
        $validatedData = $request->validate([
            'users' => 'required|array',
            'body' => 'required',
        ]);
        $recipients = $validatedData["users"];
        // iterate over the array of recipients and send a twilio request for each
        foreach ($recipients as $recipient) {
            $this->sendMessage($validatedData["body"], $recipient);
        }
        return back()->with(['success' => "Messages on their way!"]);
    }
}
