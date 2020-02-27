<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiGithubHelper;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use DB;
use Validator;

class UserController extends Controller
{
    public function show($user_id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $user_data = User::where('id', $user_id)->first();
        return response()->json(['success' => true, 'data' => $user_data]);
    }
}
