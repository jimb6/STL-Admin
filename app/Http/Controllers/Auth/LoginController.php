<?php

namespace App\Http\Controllers\Auth;

use App\Events\NewActiveAgent;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
//        if (filter_var($request->get('username'), FILTER_VALIDATE_EMAIL))
        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            Auth::logoutOtherDevices($request->password);
            if ($user->hasRole('agent'))
                abort(401);


            $user->update([
                'api_token' => $user->createToken($user->name)->plainTextToken
            ]);
            NewActiveAgent::broadcast($user);
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {

        $user = Auth::user();
        NewActiveAgent::broadcast($user);
        $user->update([
            'api_token' => null
        ]);
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    public function authenticated(Request $request, User $user)
    {
        return $request->ajax()?//if it's an AJAX request just return the user,no redirect needed!
            response()->json([
                'name'=>$user->name,
                'email'=>$user->email,
                'api_token' => $user->api_token
            ]) :
            redirect()->intended($this->redirectPath());//if it's a normal login redirect to page
    }

    public function loginAgent(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'device_serial_number' => 'required'
        ]);

        if (filter_var($request->get('username'), FILTER_VALIDATE_EMAIL))
        {
            if (!Auth::attempt([
                'email' => request('username'),
                'password' => request('password')
            ])) {
                return response(['messages' => 'invalid username or password.'], 401);
            }
        }
        else
        {
            if (!Auth::attempt([
                'contact_number' => request('username'),
                'password' => request('password')
            ])) {
                return response(['messages' => 'invalid username or password.'], 401);
            }
        }
        $user = $request->user('sanctum');
        $isDeviceOwnedByUser = $user->whereHas('device', function ($query) use ($validated) {
            $query->where('serial_number', '=', $validated['device_serial_number']);
        })->count() > 0;

        if ($isDeviceOwnedByUser){
            $accessToken = $user->createToken($request->get('username'))->plainTextToken;
            $user->session_status = true;
            $user->api_token = $accessToken;
            $user->update();
            NewActiveAgent::broadcast($user);
            return response([
                'agent' => $user
            ], 200);
        }
        else{
            return response([
                'Message' => 'The device not owned by the agent!'
            ], 401);
        }

    }

    public function logoutAgent(Request $request)
    {
        $user =  $request->user('sanctum');
        $user->session_status = false;
        $user->update();
        NewActiveAgent::broadcast($user);
        $user->currentAccessToken()->delete();
        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

}
