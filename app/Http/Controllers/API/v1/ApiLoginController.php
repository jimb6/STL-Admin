<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends ApiController
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('sanctum')->attempt([
            'contact_number' => request('username'),
            'password' => request('password')
        ])) {
            return response(['messages' => 'invalid username or password.'], 401);
        }

        $user = $request->user('agent');
        $accessToken = $user->createToken(request('username'))->plainTextToken;
        $user->api_token = $accessToken;
        $user->update();
        return response([
            'agent' =>
                [
                    'agent_id' => $user->id,
                    'agent_name' => $user->agent_name,
                    'agent_code' => $user->agent_code,
                    'agent_contact' => $user->contact_number,
                    'agent_sex' => $user->sex,
                    'agent_age' => $user->age,
                    'address' => $user->address,
                    'access_token' => $accessToken,
                    'token_type' => 'Bearer'],
            'messages' => 'You are now logged in as '.$user->guard_name,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'messages' => ['text' => "You are now logged out."]
        ]);
    }

    protected function authenticated(Request $request, $user)
    {

        return response()->json([
            'message' => Auth::guard('agent')->check()
        ]);
    }


    public function index(Request $request)
    {
        return view('api/v1/auth/login');
    }

}
