<?php

<<<<<<< HEAD
namespace App\Http\Controllers\API\v1;
=======
namespace App\Http\Controllers;
>>>>>>> develop

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
<<<<<<< HEAD
        return view('dashboard.home');
=======
        return view('home');
>>>>>>> develop
    }
}
