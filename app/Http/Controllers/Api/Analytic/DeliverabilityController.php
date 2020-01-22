<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use App\Helpers\AnalyticHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class DeliverabilityController extends Controller
{
    public function show($id){
        $analytic_helper = new AnalyticHelper();
        
        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id']);
        $sprint_cards = array();
        $deliverability_rate = array();
        $deliverability = array();
        $chart_dates = array();

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
            $rate = $card_points == 0 ? 0 : round($finished_points/$card_points * 100, 1);
            
            array_push($deliverability_rate, [$sprint_dates[$i][0]->format('d').'-'.$sprint_dates[$i][1]->format('d M'), $rate]);
        }
        
        return response()->json(['success' => true, 'data' => $deliverability_rate]);
    }
}
