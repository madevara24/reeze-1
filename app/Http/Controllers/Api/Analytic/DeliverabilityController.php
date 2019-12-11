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

        //Get cards from current project
        $card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        //Set fisrt sprint start and end date
        $date = new Carbon($project['created_at']);
        $date->setWeekStartsAt($project['sprint_start_day']);
        $date->setWeekEndsAt(
            $project['sprint_start_day'] == 0 ? 6 : $project['sprint_start_day'] -1
        );
        $date->startOfWeek();

        $sprint_dates = array();
        $deliverability = array();

        //Get all the sprint start and end date until current sprint
        while(true){
            $sprint_start_date = new Carbon($date);
            $sprint_end_date = new Carbon($date->addDays($project['sprint_duration']));

            array_push($sprint_dates, array($sprint_start_date, $sprint_end_date));

            if(Carbon::now()->between($sprint_start_date, $sprint_end_date)){
                break;
            }
        }
        
        for ($i=0; $i < count($sprint_dates); $i++) { 
            
        }
        dd($sprint_dates);
    }
}
