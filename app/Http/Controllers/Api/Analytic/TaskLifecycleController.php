<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskLifecycleController extends Controller
{
    public function show($id){
        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $id)->pluck('id')->toArray();
    }
}
