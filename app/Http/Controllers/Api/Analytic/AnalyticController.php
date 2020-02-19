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
    public function chartDates($project_id){
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

        $sprint_dates = $analytic_helper->getProjectSprintDates($project_id);

        //Get the starting card ids
        $starting_cards_ids = $analytic_helper->getCardsIds(
            $card_ids, ['planned','started','finished','accepted','rejected'], [$sprint_dates[count($sprint_dates)-1][0], $sprint_dates[count($sprint_dates)-1][1]]);
        
        //Get the total points at the start of the sprint
        $starting_cards_points = array_sum(Card::whereIn('id', $starting_cards_ids)->pluck('points')->toArray());

        $ideal_burndown = array();
        $chart_dates = array();
        $sprint_card_points = array();
        $sprint_card_ids = array();

        //Get total unfinished card points every day on the duration of the sprint and calculate perfect burndown
        for ($i=0; $i < $project['sprint_duration']; $i++) {
            //Add the date for chart label
            array_push($chart_dates, $sprint_dates[count($sprint_dates)-1][0]->format('d-M'));

            //Get the perfect burndown point and push it to array
            $perfect_burndown_point = $starting_cards_points - ($starting_cards_points * $i / ($project['sprint_duration'] - 1));
            
            //Pushing the perfect burndown to array
            array_push($ideal_burndown, round($perfect_burndown_point, 2));

            //Get the start date + i cards logs. Only take cards that are planned, started, finished, accepted, and rejected
            $newly_added_cards_ids = $analytic_helper->getCardsIds(
                $card_ids, ['planned','started','finished','accepted','rejected'], 
                [$sprint_dates[count($sprint_dates)-1][0], new Carbon($sprint_dates[count($sprint_dates)-1][0]->addDay())]);

            //Compare with previous day card ids and add new cards if not exist
            $todays_card_ids = $i == 0 ? $newly_added_cards_ids : array_unique(array_merge($sprint_card_ids[$i-1], $newly_added_cards_ids));
            
            //Get released card logs
            $todays_released_card_logs = $analytic_helper->getCardsIds(
                $card_ids, ['released'], 
                [new Carbon($sprint_dates[count($sprint_dates)-1][0]->subDay()), new Carbon($sprint_dates[count($sprint_dates)-1][0]->addDay())]);

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
            $var = array($chart_dates[$i], $sprint_card_points[$i], $ideal_burndown[$i]);
            array_push( $sprint_burndown, $var);
        }

        return response()->json(['success' => true, 'data' => $sprint_burndown]);
    }
   
    public function getProgression($project_id){   
   
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
}
