<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Github;
use Illuminate\Support\Facades\Config;
use Socialite;

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
        // Config::set('github.connections.main.token', Auth::user()->github_token);
        Github::authenticate(Auth::user()->github_token, null, 'http_token');
        dd(Github::currentUser()->repositories('all'));

        return response()->json(Auth::user());
        return view('home');
    }
}
