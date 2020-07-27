<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use JWTAuth;
use App\User;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function redirectToProvider()
    {
        return response(Socialite::driver('github')->setScopes(['read:user', 'repo'])->stateless()->redirect()->getTargetUrl());
    }

    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->stateless()->user();
        $user = User::firstOrCreate([
            'name' => is_null($githubUser->getName()) ? $githubUser->getNickname() : $githubUser->getName(),
            'github_id' =>  $githubUser->getId()
            ]);
            
        if ($user != null) {
            $user->github_token = $githubUser->token;
            $user->save();
        }

        $token = JWTAuth::fromUser($user);

        return view('callback', [
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
}
