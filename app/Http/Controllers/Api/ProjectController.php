<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiGithubHelper;
use App\Http\Controllers\Controller;
use App\Model\Card;
use App\Model\CardLog;
use App\Model\Project;
use App\Model\ProjectMember;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use DB;
use Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $projects = DB::table('project_members')
                    ->select('pic.name as pic_name', 'projects.*')
                    ->join('projects', 'project_members.project_id', 'projects.id')
                    ->join('users as pic', 'projects.pic_id', 'pic.id')
                    ->where('project_members.user_id', $user->id)
                    ->get();
        return response()->json(['success' => true, 'data' => $projects]);
    }

    public function member($project_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if(ProjectMember::where('project_id', $project_id)->count() === 0)
        {
            return response()->json([
                'error' => 'Project Not Found'
                ], 404);
        }

        $projectMembers = DB::table('project_members')
                        ->select('users.name as username', 'users.email', 'projects.*')
                        ->join('users', 'project_members.user_id', 'users.id')
                        ->join('projects', 'project_members.project_id', 'projects.id')
                        ->where('project_members.project_id', $project_id)
                        ->get();
                        return response()->json(['success' => true, 'data' => $projectMembers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'repository' => 'required',
            'description' => 'required|max:255',
            'sprint_duration' => 'required|numeric',
            'sprint_start_day' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        DB::beginTransaction();
        try{
            $project = Project::create([
                'name' => $request->name,
                'repository' => $request->repository,
                'pic_id' => $user->id,
                'description' => $request->description,
                'sprint_duration' => $request->sprint_duration,
                'sprint_start_day' => $request->sprint_start_day,
                'version' => '0.0.0'
            ]);
            $projectMember = new ProjectMember(['user_id' => $user->id]);
            $project->project_member()->save($projectMember);
            DB::commit();   
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['errors' => $e], 422);
        }
        
        return response()->json(['success' => true], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'repository' => 'required',
            'description' => 'required|max:255',
            'sprint_duration' => 'required|numeric',
            'sprint_start_day' => 'required|numeric'
        ]);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        DB::beginTransaction();
        try{
            $project = Project::find($id);
            $project->update([
                'name' => $request->name,
                'repository' => $request->repository,
                'description' => $request->description,
                'sprint_duration' => $request->sprint_duration,
                'sprint_start_day' => $request->sprint_start_day
            ]);
            DB::commit();   
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['errors' => $e], 422);
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();

        return response()->json(['success' => true], 200);
    }

    public function getListRepository()
    {
        $listRepo = ApiGithubHelper::getRepositoryList();

        return response()->json(['success' => true, 'data' => $listRepo], 200);
    }

    public function release(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::find($id);
        $branchCount = count($request->card_branch);

        //Get Project Release Type (Major/Minor/Patch)
        $releaseType = $request->release_type;
        
        if($releaseType === "Major")
        {
            //Split project version
            $projectVersion = explode('.', $project->version);

            //Cast first index to int then add by 1
            (int)$projectVersion[0] += 1;

            //Cast first index back to string
            $projectVersion[0] = (string)$projectVersion[0];

            //Merge the array back to string
            $projectVersion = implode('.', $projectVersion);

            $project->version = $projectVersion;
        }else if($releaseType === "Minor")
        {
            $projectVersion = explode('.', $project->version);
            (int)$projectVersion[1] += 1;
            $projectVersion[1] = (string)$projectVersion[1];
            $projectVersion = implode('.', $projectVersion);

            $project->version = $projectVersion;
        }else if($releaseType === "Patch")
        {
            $projectVersion = explode('.', $project->version);
            (int)$projectVersion[2] += 1;
            $projectVersion[2] = (string)$projectVersion[2];
            $projectVersion = implode('.', $projectVersion);

            $project->version = $projectVersion;
        }else{
            return response()->json(['errors' => "Invalid project release type"], 422);
        }

        $releaseBranchName = 'release-'. $project->version;
        $releaseBranch = ApiGithubHelper::showGithubBranch($user, $project, $releaseBranchName);
        
        //Check if branch already exists
        if(is_null($releaseBranch)){
            $releaseBranch = ApiGithubHelper::createReleaseBranch($user, $project);
        }
        
        $shouldMergeMaster = true;
        $statusMergeBranch = null;

        for($i = 0; $i < $branchCount; $i++)
        {
            $cardId = Card::where('github_branch_name', $request->card_branch[$i])->first()->id;
            $cardState = CardLog::where('card_id', $cardId)->first();
            $listOpenPullRequests = ApiGithubHelper::getOpenPullRequest($user, $project);
            $pullRequest = [];

            if($cardState !== 'released')
            {
                if(count($listOpenPullRequests) !== 0)
                {
                    foreach($listOpenPullRequests as $openPullRequest)
                    {
                        if($openPullRequest['title'] == $request->card_branch[$i])
                        {
                            array_push($pullRequest, $openPullRequest);
                        }
                    }
                }else{
                    array_push($pullRequest, ApiGithubHelper::createPullRequest($user, $project, $request->card_branch[$i], $releaseBranchName));
                }
                
                if(!empty($pullRequest))
                {
                    $statusMergeBranch = ApiGithubHelper::mergeGithubBranch($user, $project, $pullRequest[0], $releaseBranch);
                    if($statusMergeBranch !== null)
                    {
                        CardLog::create([
                            'card_id' => $cardId,
                            'state' => 'released'
                        ]);
                        $shouldMergeMaster = true;
                    }else{
                        $shouldMergeMaster = false;
                    }
                }
            }
        }
        
        if($shouldMergeMaster)
        {
            //Final merge release
            $pullRequest = ApiGithubHelper::createPullRequest($user, $project, $releaseBranchName, 'master');
            $statusMergeMaster = ApiGithubHelper::mergeGithubBranch($user, $project, $pullRequest);

            if($statusMergeMaster !== null)
            {
                $project->save();
            }
            return response()->json(['success' => true], 200);
        }else{
            return response()->json(['errors' => 'Something went wrong when trying to merge release.'], 422);
        }
    }

    public function show($project_id){
        $user = JWTAuth::parseToken()->authenticate();

        if(ProjectMember::where([['project_id', $project_id],['user_id', $user->id]])->count() === 0)
        {
            return response()->json([
                'error' => 'Project Not Found'
                ], 404);
        }

        $project = Project::where('id', $project_id)->first();
        return response()->json(['success' => true, 'data' => $project]);
    }
}
