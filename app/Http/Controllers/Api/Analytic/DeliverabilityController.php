<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class DeliverabilityController extends Controller
{
    public function show($id){
        //Get current project
        $project = Project::where('id', $id)->first();

        
    }
}
