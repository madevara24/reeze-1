<?php

namespace App\Helpers;

use App\Model\Card;
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

    public static function createGithubBranch($user, $projectRepository, $branchName)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $projectRepository->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];
        
        try{
            $branchMasterData = Github::gitData()->references()->show($repositoryUser, $repositoryName, 'heads/master');
        
            $newBranchName = ApiGithubHelper::randomNumber($branchName);
            
            $param['ref'] = 'refs/heads/' . $newBranchName;
            $param['sha'] = $branchMasterData['object']['sha'];
            
            Github::gitData()->references()->create($repositoryUser, $repositoryName, $param);
        }catch(\Exception $e){
            return response()->json(['errors' => $e], 422);
        }
        
        return $newBranchName;
    }

    private static function randomNumber($branchName)
    {
        $randomNumber = mt_rand(100000000, 999999999);
        
        $newBranchName = $randomNumber . '-' .$branchName;

        return ApiGithubHelper::checkUnique($newBranchName);
    }

    private static function checkUnique($newBranchName)
    {
        $card = Card::where('github_branch_name', $newBranchName)->first();

        if($card !== null)
        {
            ApiGithubHelper::randomNumber($newBranchName);
        }

        return $newBranchName;
    }
}
