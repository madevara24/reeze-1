<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use App\Helpers\AnalyticHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class TaskLifecycleController extends Controller
{
    
    public function show($id){
        
        $analytic_helper = new AnalyticHelper();

        $task_lifecycle = array(
            'planned' => array(),
            'started' => array(),
            'finished' => array(),
            'accepted' => array(),
            'rejected' => array()
        );

        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $project_card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        //Get sprint dates for the last 5 sprints
        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id']);

        //Get the ids of the cards that have activity 
        $card_ids = CardLog::whereIn('card_id', $project_card_ids)
            ->where('state', '!=' , 'created')
            ->whereBetween('created_at', [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]])
            ->groupBy('card_id')->pluck('card_id')->toArray();

        foreach ($card_ids as $card_id) {

            $card_logs = CardLog::where('card_id', $card_id)
                ->where('state', '!=' , 'created')
                ->whereBetween('created_at', [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]])
                ->get()->toArray();

            foreach ($card_logs as $key => $card_log) {

                //Released logs don't need to be calculated since it's the last state
                if($card_log['state'] == 'released')
                    break;
                    
                //Search the state before and calculate the duration before the first log of the 5 sprints
                if($key == 0 && $card_log['state'] != 'planned'){
                    $state_before = CardLog::where('card_id', $card_log['card_id'])
                        ->where('created_at', '<', $sprint_dates[0][0])
                        ->orderBy('created_at', 'desc')->first()->toArray();

                    //Get the duration from the start of the sprint time to the first log, to be set as the duration of the state before
                    $duration = $sprint_dates[0][0]->diffInMinutes($card_log['created_at']);

                    array_push($task_lifecycle[$state_before['state']], $duration);
                }

                //If its the last log, count until now
                if($key == count($card_logs) -1){
                    $log_time = new Carbon($card_log['created_at']);
                    $duration = $log_time->diffInMinutes(Carbon::now());

                    array_push($task_lifecycle[$card_log['state']], $duration);

                }else{
                    $log_start_time = new Carbon($card_log['created_at']);
                    $log_end_time = new Carbon($card_logs[$key + 1]['created_at']);

                    $duration = $log_start_time->diffInMinutes($log_end_time);

                    array_push($task_lifecycle[$card_log['state']], $duration);

                }
            }
        }
        
        foreach ($task_lifecycle as $state => $state_duration) {
            $div = count($state_duration);
            $task_lifecycle[$state] = array($state, round(array_sum($state_duration) / ($div * 60), 1));
        }

        return response()->json(['success' => true, 'data' => array_values($task_lifecycle)]);
    }
}
