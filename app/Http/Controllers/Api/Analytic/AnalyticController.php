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

    public function burndown($project_id){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();

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
            
            //Pushing the perfect burndown to array, still on the fence of rounding it or not
            //array_push($ideal_burndown, intval(round($perfect_burndown_point)));
            array_push($ideal_burndown, round($perfect_burndown_point, 2));

            //Get the start date + i cards logs
            //Only take cards that are planned, started, finished, accepted, and rejected
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
            
            //Push todays cards ids
            //Is used to detect cards without logs but havent released yet on the next day
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

        //return array($sprint_card_points, $ideal_burndown, $chart_dates);
        return response()->json(['success' => true, 'data' => $sprint_burndown]);
    }
   
    public function getProgression($id){   
   
    }   
   
    public function deliverability($project_id){   
        $analytic_helper = new AnalyticHelper();
        
        //Get current project
        $project = Project::where('id', $project_id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $project_id)->pluck('id')->toArray();

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
}
