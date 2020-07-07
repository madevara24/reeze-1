<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use App\Helpers\AnalyticHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class AnalyticController extends Controller
{
    public function currentSprintDates($project_id){
        $analytic_helper = new AnalyticHelper();
        $sprint_dates = $analytic_helper->getProjectCurrentSprintDates($project_id);
        $formated_dates = array();
        foreach ($sprint_dates as $sprint_date) {
            array_push($formated_dates, $sprint_date[0]->format('d M'));
        }
        return response()->json(['success' => true, 'data' => $formated_dates]);
    }

    public function currentSprintDay($project_id){
        $analytic_helper = new AnalyticHelper();
        $sprint_day = $analytic_helper->getCurrentDayOfSprint($project_id);
        return response()->json(['success' => true, 'data' => $sprint_day]);
    }

    public function sprintDates($project_id){
        $analytic_helper = new AnalyticHelper();
        $sprint_dates = $analytic_helper->getProjectSprintDates($project_id);
        $formated_dates = array();
        foreach ($sprint_dates as $sprint_date) {
            array_push($formated_dates, $sprint_date[0]->format('d').'-'.$sprint_date[1]->format('d M'));
        }
        return response()->json(['success' => true, 'data' => $formated_dates]);
    }

    public function sprintProgression($project_id, $user_id = null){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Checks if this request is for whole project or for single user, determines the analyzed cards
        if ($user_id) {
            $card_ids = Card::where([['project_id', $project_id],['owner', $user_id]])->pluck('id')->toArray();
        }else {
            $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();
        }

        $sprint_dates = $analytic_helper->getProjectCurrentSprintDates($project_id);

        //Get the starting card ids
        $starting_cards_ids = $analytic_helper->getCardsIds(
            $card_ids, ['planned','started','finished','accepted','rejected'], [$sprint_dates[0][0], $sprint_dates[0][1]]);
        
        //Get the total points at the start of the sprint
        $starting_cards_points = array_sum(Card::whereIn('id', $starting_cards_ids)->pluck('points')->toArray());

        $ideal_burndown = array();
        $chart_dates = array();
        $sprint_card_points = array();
        $sprint_card_ids = array();

        //Get total unfinished card points every day on the duration of the sprint and calculate perfect burndown
        for ($i=0; $i < $project['sprint_duration']; $i++) {
            //Add the date for chart label
            array_push($chart_dates, $sprint_dates[$i][0]->format('d-M'));

            //Get the perfect burndown point and push it to array
            $perfect_burndown_point = $starting_cards_points - ($starting_cards_points * $i / ($project['sprint_duration'] - 1));
            
            //Pushing the perfect burndown to array
            array_push($ideal_burndown, round($perfect_burndown_point, 2));

            //Get the start date + i cards logs. Only take cards that are planned, started, finished, accepted, and rejected
            $newly_added_cards_ids = $analytic_helper->getCardsIds(
                $card_ids, ['planned','started','finished','accepted','rejected'], 
                [$sprint_dates[$i][0], $sprint_dates[$i][1]]);

            //Compare with previous day card ids and add new cards if not exist
            $todays_card_ids = $i == 0 ? $newly_added_cards_ids : array_unique(array_merge($sprint_card_ids[$i-1], $newly_added_cards_ids));
            
            //Get released card logs
            $todays_released_card_logs = $analytic_helper->getCardsIds(
                $card_ids, ['released'], 
                [$sprint_dates[$i][0], $sprint_dates[$i][1]]);

            //Subtract the released cards ids
            $todays_card_ids = array_diff($todays_card_ids, $todays_released_card_logs);
            
            //Push todays cards ids. Is used to detect cards without logs but havent released yet on the next day
            array_push($sprint_card_ids, $todays_card_ids);
            
            //Sum up todays cards points
            $todays_card_points = array_sum(Card::whereIn('id', $todays_card_ids)->pluck('points')->toArray());

            //Push todays cards total points
            array_push($sprint_card_points, $todays_card_points);
        }

        $sprint_burndown = array();

        for ($i=0; $i < $project['sprint_duration']; $i++) {
            $var = array($sprint_card_points[$i], $ideal_burndown[$i]);
            array_push( $sprint_burndown, $var);
        }

        return response()->json(['success' => true, 'data' => $sprint_burndown]);
    }
   
    public function deliverability($project_id, $user_id = null){   
        $analytic_helper = new AnalyticHelper();
        
        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Checks if this request is for whole project or for single user, determines the analyzed cards
        if ($user_id) {
            $card_ids = Card::where([['project_id', $project_id],['owner', $user_id]])->pluck('id')->toArray();
        }else {
            $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();
        }

        $sprint_dates = $analytic_helper->getProjectSprintDates($project_id);
        $sprint_cards = array();
        $deliverability_rate = array();

        //Get every sprint released and not-released cards
        for ($i=0; $i < count($sprint_dates); $i++) {

            //Get cards that have activity/log between the sprint duration
            $sprint_cards = $analytic_helper->getCardsIds(
                $card_ids, ['planned','started','finished','accepted','rejected'], [$sprint_dates[$i][0], $sprint_dates[$i][1]]);

            //Get the cards that don't have activity/log but haven't released yet
            $unreleased_cards = $analytic_helper->getCardsIds(
                $card_ids, ['planned','started','finished','accepted','rejected'], [$project['created_at'], $sprint_dates[$i][1]]);

            //Get the cards that are already released to subtract the unreleased cards above
            $released_cards = $analytic_helper->getCardsIds(
                $card_ids, ['released'], [$project['created_at'], $sprint_dates[$i][1]]);
                
            $finished_sprint_cards = $analytic_helper->getCardsIds(
                $card_ids, ['released'], [$sprint_dates[$i][0], $sprint_dates[$i][1]]);

            //Remove the released card from the unreleased but don't have log list
            $unreleased_cards = array_diff($unreleased_cards, $released_cards);

            //Merge sprint cards with cards that don't have activity
            $sprint_cards = array_unique(array_merge($sprint_cards, $unreleased_cards));

            //Calculate the total points
            $card_points = array_sum(Card::whereIn('id', $sprint_cards)->pluck('points')->toArray());

            //Calculate the finished points
            $finished_points = array_sum(Card::whereIn('id', $finished_sprint_cards)->pluck('points')->toArray());

            //Calculate deliverability rate
            $rate = $card_points == 0 ? 0 : round($finished_points/$card_points * 100, 1);
            
            array_push($deliverability_rate, $rate);
        }
        
        return response()->json(['success' => true, 'data' => $deliverability_rate]);
    }

    public function rejection($project_id, $user_id = null){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Checks if this request is for whole project or for single user, determines the analyzed cards
        if ($user_id) {
            $card_ids = Card::where([['project_id', $project_id],['owner', $user_id]])->pluck('id')->toArray();
        }else {
            $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();
        }

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
                $sprint_logs = $analytic_helper->getCardLogs(
                    $card_ids[$j], ['planned','started','finished','accepted','rejected','released'], [$sprint_dates[$i][0], $sprint_dates[$i][1]]);

                //Get the index of rejected log
                $rejected_key = array_search('rejected', array_column($sprint_logs, 'state'));

                //Check if there is a rejected state on this sprint, if not, break
                if(!$rejected_key)
                    break;
                    
                //Check if the card cycle has already started the sprint before
                $yesterday_sprint_logs = $analytic_helper->getCardLogs(
                    $card_ids[$j], ['planned','started','finished','accepted','rejected','released'], [$sprint_dates[0][0], $sprint_dates[$i][0]]);

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
                $rejection_rate[$i] = round(array_sum($rejection_rate[$i])/count($rejection_rate[$i]), 1);
            }else{
                $rejection_rate[$i] = 0;
            }
        }

        return response()->json(['success' => true, 'data' => $rejection_rate]);
    }

    public function taskLifecycle($project_id, $user_id = null){
        
        $analytic_helper = new AnalyticHelper();

        $task_lifecycle = array(
            'planned' => array(),
            'started' => array(),
            'finished' => array(),
            'accepted' => array(),
            'rejected' => array()
        );

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Checks if this request is for whole project or for single user, determines the analyzed cards
        if ($user_id) {
            $project_card_ids = Card::where([['project_id', $project_id],['owner', $user_id]])->pluck('id')->toArray();
        }else {
            $project_card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();
        }

        //Get sprint dates for the last 5 sprints
        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id']);

        //Get the ids of the cards that have activity
        $card_ids = $analytic_helper->getCardsIds(
            $project_card_ids, ['planned','started','finished','accepted','rejected','released'], [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]]);

        foreach ($card_ids as $card_id) {
            $card_logs = $analytic_helper->getCardLogs(
                $card_id, ['planned','started','finished','accepted','rejected','released'], [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]]);

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
            if($div == 0){
                $task_lifecycle[$state] = array($state, 0);
            }else{
                $task_lifecycle[$state] = array($state, round(array_sum($state_duration) / ($div * 60), 1));
            }
        }

        return response()->json(['success' => true, 'data' => array_values($task_lifecycle)]);
    }

    public function estimate($project_id){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Get cards from current project
        $project_card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();

        //Get sprint dates for the last 5 sprints
        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id'], 6);

        //Get points from story that is released
        $released_card_ids = $analytic_helper->getCardsIds(
            $project_card_ids, ['released'], [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]]);
            
        $released_cards_points = Card::whereIn('id', $released_card_ids)->pluck('points')->toArray();

        $released_cards_points = $analytic_helper->convertArrayStringToArrayInt($released_cards_points);

        $velocity = (int)round(array_sum($released_cards_points)/(count($sprint_dates) - 1), 0);

        if(count($released_cards_points) == 0){
            $average_card_points = 0;
        }else{
            $average_card_points = array_sum($released_cards_points)/count($released_cards_points);
        }

        $unfinished_cards_points = Card::whereIn('id', array_diff($project_card_ids, $released_card_ids))->pluck('points')->toArray();

        $unfinished_cards_points = $analytic_helper->convertArrayStringToArrayInt($unfinished_cards_points);
        
        foreach ($unfinished_cards_points as $key => $unfinished_cards_point) {
            if($unfinished_cards_point == 0)
                $unfinished_cards_points[$key] = $average_card_points;
        }

        $unfinished_cards_points = (int)round(array_sum($unfinished_cards_points), 0);

        if($velocity ==0){
            $estimated_sprints_left = 0;
        }else{
            $estimated_sprints_left = (int)round($unfinished_cards_points/$velocity);
        }

        return response()->json(['success' => true, 'data' => ['velocity' => $velocity, 'estimate' => $estimated_sprints_left]]);
    }

    public function cardTimeline($project_id, $user_id = null){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Checks if this request is for whole project or for single user, determines the analyzed cards
        if ($user_id) {
            $card_ids = Card::where([['project_id', $project_id],['owner', $user_id]])->pluck('id')->toArray();
        }else {
            $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();
        }

        $sprint_dates = $analytic_helper->getProjectCurrentSprintDates($project_id);
        
        $timeline_data = [];

        foreach ($card_ids as $card_id) {
            $card_info = Card::where('id', $card_id)->get()->toArray();
            $card_logs = $analytic_helper->getCardLogs(
                $card_id, ['planned','started','finished','accepted','rejected'], [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]]);
                
            $released_log = $analytic_helper->getCardLogs(
                $card_id, ['released'], [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]]);
            
            $card_end_date = $sprint_dates[count($sprint_dates) - 1][1];
            if($released_log)
                $card_end_date = $released_log[0]['created_at'];
                
            if($card_logs){
                for ($i=0; $i < count($card_logs); $i++) {
                    if($i == count($card_logs) - 1)
                        $end_date = $card_end_date;
                    else
                        $end_date = $card_logs[$i + 1]['created_at'];

                    $card_data = $analytic_helper->formatCardTimelineData($card_info[0], $card_logs[$i], $end_date);
                    array_push($timeline_data, $card_data);
                }
            }
        }

        return response()->json(['success' => true, 'data' => $timeline_data]);
    }
}
