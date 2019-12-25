<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AnalyticHelper;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class RejectionController extends Controller
{
    public function show($id){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id']);
        $rejection_rate = array();

        for ($i=0; $i < count($sprint_dates); $i++) {

            $rejection_rate[$i] = array();

            for ($j=0; $j < count($card_ids); $j++) {
                $card_cycle_starts = null;
                $card_cycle_ends = null;
                $rejected_cycle_starts = null;
                $rejected_cycle_ends = null;

                //Get cards that have activity/log between the sprint duration
                $sprint_logs = CardLog::where('card_id', $card_ids[$j])
                    ->whereIn('state',['planned','started','finished','accepted','rejected','released'])
                    ->whereBetween('created_at', [$sprint_dates[$i][0], $sprint_dates[$i][1]])
                    ->get()->toArray();

                //Get the index of rejected log
                $rejected_key = array_search('rejected', array_column($sprint_logs, 'state'));

                //Check if there is a rejected state on this sprint, if not, break
                if(!$rejected_key)
                    break;
                    
                //Check if the card cycle has already started the sprint before
                $yesterday_sprint_logs = CardLog::where('card_id', $card_ids[$j])
                    ->where('state', '!=' , 'created')
                    ->whereBetween('created_at', [$sprint_dates[0][0], $sprint_dates[$i][0]])
                    ->get()->toArray();

                
                //set the card cycle start for this sprint
                if(count($yesterday_sprint_logs) > 0){
                    $card_cycle_starts = $sprint_dates[$i][0];
                }else{
                    $card_cycle_starts = new Carbon($sprint_logs[array_search('planned', array_column($sprint_logs, 'state'))]['created_at']);
                }

                //Find a released log for the end of rejection cycle if exist
                $released_key = array_search('released', array_column($sprint_logs, 'state'));

                if($released_key){
                    $card_cycle_ends = new Carbon($sprint_logs[array_search('released', array_column($sprint_logs, 'state'))]['created_at']);
                }else{
                    $card_cycle_ends = $sprint_dates[$i][1];
                }

                //Count the minutes between the card cycle on this sprint as total card cycle duration
                $total_cycle_time = $card_cycle_starts->diffInMinutes($card_cycle_ends);

                //Set the start time for rejection cycle
                $rejected_cycle_starts = new Carbon($sprint_logs[$rejected_key]['created_at']);

                //Check if there is another state log after rejected to determine the end of rejection cycle
                if(count($sprint_logs) <= $rejected_key + 1){
                    $rejected_cycle_ends = $sprint_dates[$i][1];
                }else{
                    $rejected_cycle_ends = new Carbon($sprint_logs[$rejected_key + 1]['created_at']);
                }

                //Get the duration when the card is on rejected state on this sprint
                $rejection_cycle_time = $rejected_cycle_starts->diffInMinutes($rejected_cycle_ends);

                $card_sprint_rejection = $rejection_cycle_time / $total_cycle_time * 100;
                array_push($rejection_rate[$i], $card_sprint_rejection);
            }
            if(count($rejection_rate[$i]) != 0){
                $rejection_rate[$i] = round(array_sum($rejection_rate[$i])/count($rejection_rate[$i]), 2);
            }else{
                $rejection_rate[$i] = 0;
            }
        }
        return $rejection_rate;
    }
}
