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
        $sprint_cards = array();
        $deliverability_rate = array();
        $deliverability = array();
        $chart_dates = array();

        //Get all the sprint start and end date until current sprint
        while(true){
            $sprint_start_date = new Carbon($date);
            $sprint_end_date = new Carbon($date->addDays($project['sprint_duration']));

            array_push($sprint_dates, array($sprint_start_date, $sprint_end_date));

            if(Carbon::now()->between($sprint_start_date, $sprint_end_date)){
                break;
            }
        }
        
        //Cut the array to only get the last 5 iteration
        while (count($sprint_dates) > 6) {
            $sprint_dates = array_shift($sprint_dates);
        }

        //Get every sprint released and not-released cards
        for ($i=0; $i < count($sprint_dates); $i++) {

            //Get cards that have activity/log between the sprint duration
            $sprint_cards = array_column(CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['planned','started','finished','accepted','rejected'])
                ->whereBetween('created_at', [$sprint_dates[$i][0], $sprint_dates[$i][1]])
                ->groupBy('card_id')
                ->get()->toArray(), 'card_id');

            //Get the cards that don't have activity/log but haven't released yet
            $unreleased_cards = array_column(CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['planned','started','finished','accepted','rejected'])
                ->whereBetween('created_at', [$project['created_at'], $sprint_dates[$i][1]])
                ->groupBy('card_id')
                ->get()->toArray(), 'card_id');

            //Get the cards that are already released to subtract the unreleased cards above
            $released_cards = array_column(CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['released'])
                ->whereBetween('created_at', [$project['created_at'], $sprint_dates[$i][1]])
                ->groupBy('card_id')
                ->get()->toArray(), 'card_id');
                
            $finished_sprint_cards = array_column(CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['released'])
                ->whereBetween('created_at', [$sprint_dates[$i][0], $sprint_dates[$i][1]])
                ->groupBy('card_id')
                ->get()->toArray(), 'card_id');

            //Remove the released card from the unreleased but don't have log list
            $unreleased_cards = array_diff($unreleased_cards, $released_cards);

            //Merge sprint cards with cards that don't have activity
            $sprint_cards = array_unique(array_merge($sprint_cards, $unreleased_cards));

            //Calculate the total points
            $card_points = array_sum(Card::whereIn('id', $sprint_cards)->pluck('points')->toArray());

            //Calculate the finished points
            $finished_points = array_sum(Card::whereIn('id', $finished_sprint_cards)->pluck('points')->toArray());

            //Calculate deliverability rate
            $rate = $card_points == 0 ? -1 : round($finished_points/$card_points * 100, 2);
            
            array_push($deliverability_rate, [$sprint_dates[$i][0]->format('d').'-'.$sprint_dates[$i][1]->format('d M'), $rate]);
        }
        
        return $deliverability_rate;
    }
}
