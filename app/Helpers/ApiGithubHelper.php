<?php

namespace App\Helpers;

use App\Model\Card;
use Carbon\Carbon;
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
            return null;
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

    public static function getOpenPullRequest($user, $projectRepository)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $projectRepository->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];
        
        try{
            $pullRequest = Github::pullRequest()->all($repositoryUser, $repositoryName, ['state' => 'open']);
            return $pullRequest;
        }catch(\Exception $e)
        {
            return null;
        }
    }

    public static function createPullRequest($user, $projectRepository, $branchName, $baseBranchName)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $projectRepository->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];
        
        try{
            $pullRequest = Github::pullRequest()->create($repositoryUser, $repositoryName, [
                'base' => $baseBranchName,
                'head' => $branchName,
                'title' => $branchName,
                'body' => $branchName,
            ]);
            return $pullRequest;
        }catch(\Exception $e)
        {
            return null;
        }
    }

    public static function createReleaseBranch($user, $project)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $project->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];

        try{
            $branchMasterData = Github::gitData()->references()->show($repositoryUser, $repositoryName, 'heads/master');
        
            $releaseBranchName = 'release-'. $project->version;
            
            $param['ref'] = 'refs/heads/' . $releaseBranchName;
            $param['sha'] = $branchMasterData['object']['sha'];
            
            return Github::gitData()->references()->create($repositoryUser, $repositoryName, $param);
        }catch(\Exception $e){
            return $releaseBranchName;
        }
    }

    public static function showGithubBranch($user, $project, $branchName)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $project->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];

        try{
            return Github::gitData()->references()->show($repositoryUser, $repositoryName, 'heads/' . $branchName);
        }catch(\Exception $e)
        {
            return null;
        }
    }

    public static function mergeGithubBranch($user, $project, $pullRequestedBranch)
    {
        Github::authenticate($user->github_token, null, 'http_token');
        $repository = explode('/', $project->repository);
        
        $repositoryUser = $repository[0];
        $repositoryName = $repository[1];
        try{
            return Github::pullRequest()->merge($repositoryUser, $repositoryName, $pullRequestedBranch['number'], $pullRequestedBranch['title'], $pullRequestedBranch['head']['sha']);
        }catch(\Exception $e)
        {
            dd($e);
            return null;
        }
    }
}
