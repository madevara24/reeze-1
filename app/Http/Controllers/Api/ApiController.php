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
        return Socialite::driver('github')->scopes(['read:user', 'repo', 'admin:org_hook'])->stateless()->redirect();
    }

    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->stateless()->user();
        $user = User::query()->firstOrNew(['email' => $githubUser->getEmail()]);

        if (!$user->exists) {
            $user->name = is_null($githubUser->getName()) ? '' : $githubUser->getName();
            $user->github_token = $githubUser->token;
            $user->github_id = $githubUser->getId();
            $user->save();
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'token' => $token
        ]);;
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
