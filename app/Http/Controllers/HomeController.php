<?php

namespace App\Http\Controllers;

use App\Events\UserEvent;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard.home');
    }

    public function show()
    {
        $user = auth()->user();
        event(new UserEvent($user)); // broadcast `ScoreUpdated` event
        return redirect()->back()->withValue($user);
    }
}
