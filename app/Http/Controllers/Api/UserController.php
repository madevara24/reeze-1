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

        $user_data = DB::table('users')
            ->select('id as user_id','name as username', 'email','github_id')
            ->where('id', $user_id)->first();
        return response()->json(['success' => true, 'data' => $user_data]);
    }

    public function getAuthUser(){
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json(['data' => $user]);
    }
}
