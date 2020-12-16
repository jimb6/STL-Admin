<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    protected function loggedOut(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
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

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
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
            $accessToken = $user->createToken(request('username'))->plainTextToken;
            $user->update();
            return response([
                $accessToken
            ], 200);
        }
        else{
            return response([
                'Message' => 'The device have been used not owned by the agent!'
            ], 200);
        }

    }


}
