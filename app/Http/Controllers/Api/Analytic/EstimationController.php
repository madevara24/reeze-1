<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use App\Helpers\AnalyticHelper;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class EstimationController extends Controller
{
    public function show($id){
        $analytic_helper = new AnalyticHelper();

        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $project_card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        //Get sprint dates for the last 5 sprints
        $sprint_dates = $analytic_helper->getProjectSprintDates($project['id'], 6);

        //Get points from story that is released
        $released_card_ids = CardLog::whereIn('card_id', $project_card_ids)
            ->where('state', 'released')
            ->whereBetween('created_at', [$sprint_dates[0][0], $sprint_dates[count($sprint_dates) - 1][1]])
            ->groupBy('card_id')->pluck('card_id')->toArray();

        $released_cards_points = Card::whereIn('id', $released_card_ids)->pluck('points')->toArray();

        $released_cards_points = $analytic_helper->convertArrayStringToArrayInt($released_cards_points);

        $velocity = array_sum($released_cards_points)/(count($sprint_dates) - 1);

        $average_card_points = array_sum($released_cards_points)/count($released_cards_points);

        $unfinished_cards_points = Card::whereIn('id', array_diff($project_card_ids, $released_card_ids))->pluck('points')->toArray();

        $unfinished_cards_points = $analytic_helper->convertArrayStringToArrayInt($unfinished_cards_points);
        
        foreach ($unfinished_cards_points as $key => $unfinished_cards_point) {
            if($unfinished_cards_point == 0)
                $unfinished_cards_points[$key] = $average_card_points;
        }

        $unfinished_cards_points = (int)round(array_sum($unfinished_cards_points), 0);

        $estimated_sprints_left = (int)round($unfinished_cards_points/$velocity);

        return array($velocity, $estimated_sprints_left);
    }
}
