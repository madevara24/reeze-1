<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use App\Model\ProjectMember;

class UserProjectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userProject = ProjectMember::where('user_id', $user->id)
                                    ->where('project_id', $request->route('project_id'))
                                    ->get();
        if(count($userProject) === 0){
            return response()->json([
                'error' => 'Project Not Found'
                ], 404);
        }

        return $next($request);
    }
}
