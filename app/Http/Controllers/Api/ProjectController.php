<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiGithubHelper;
use App\Http\Controllers\Controller;
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

        try{
            $project = Project::create([
                'name' => $request->name,
                'repository' => $request->repository,
                'pic_id' => $user->id,
                'description' => $request->description,
                'sprint_duration' => $request->sprint_duration,
                'sprint_start_day' => $request->sprint_start_day
            ]);
            $projectMember = new ProjectMember(['user_id' => $user->id]);
            $project->project_member()->save($projectMember);
        }catch(\Exception $e){
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

        try{
            $project = Project::find($id);
            $project->update([
                'name' => $request->name,
                'repository' => $request->repository,
                'description' => $request->description,
                'sprint_duration' => $request->sprint_duration,
                'sprint_start_day' => $request->sprint_start_day
            ]);
        }catch(\Exception $e){
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
}
