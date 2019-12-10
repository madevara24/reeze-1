<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();
        $authUser = $this->findOrCreateUser($user);
        Auth::login($authUser, true);
        return redirect('/home');
    }

    public function findOrCreateUser($user)
    {
        $authUser = User::where('github_id', $user->id)->first();
        if ($authUser) {
            $authUser->github_token = $user->token;
            $authUser->save();
            return $authUser;
        }
        else{
            $data = User::create([
                'name'     => is_null($user->name) ? explode('@', $user->email)[0] : $user->name,
                'email'    => !empty($user->email)? $user->email : '' ,
                'github_token'     => $user->token,
                'github_id' => $user->id
            ]);
            return $data;
        }
    }
}
