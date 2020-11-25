<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AgentLoginController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:agent')->except('logout');
    }

    public function login(Request $request)
    {
        Log::info('Validate Information.');
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        Log::info('validated');

        Log::info('Authenticating...');
        if (!Auth::guard('agent')->attempt([
            'agent_name' => request('username'),
            'password' => request('password')
        ])) {
            Log::info('Un authorize user');
            return response(['error' => ['unauthenticated']]);
        }

        Log::info('Authenticated user!');
        Log::info('Generating Api Token...');

        $user = $request->user('agent');
        $accessToken = $user->createToken( $request->input('username'));
        $token = $accessToken->token;
        $token->save();
        Log::info('Access token generated: ');
        Log::info('Returning Data...');
        return response([
            'set_attributes' =>
                ['agent_name' => $user->agent_name,
                    'agent_code' => $user->agent_code,
                    'agent_contact' => $user->contact_number,
                    'agent_sex' => $user->sex,
                    'agent_age' => $user->age,
                    'address' => $user->address,
                    'access_token' => $accessToken->accessToken,
                    'token_type' => 'Bearer'],
            'messages' => [
                'text' => 'You are now connected!'],
        ]);
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
            'message' => Auth::guard('admin')->check()
        ]);
    }



    public function index(Request $request)
    {
        return view('api/v1/auth/login');
    }

}
