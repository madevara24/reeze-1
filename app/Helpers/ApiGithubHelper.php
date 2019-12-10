<?php

namespace App\Helpers;

use Github;
use JWTAuth;

class ApiGithubHelper
{
    public static function getRepositoryList()
    {
        $user = JWTAuth::parseToken()->authenticate();
        Github::authenticate($user->github_token, null, 'http_token');
        $allRepo = Github::currentUser()->repositories('all');
        $repoList = [];

        foreach($allRepo as $repo){
            $repoList[] = $repo['full_name'];
        }
        return $repoList;
    }
}
