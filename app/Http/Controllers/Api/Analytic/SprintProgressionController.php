<?php

namespace App\Http\Controllers\Api\Analytic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class SprintProgressionController extends Controller
{
    public function show($id)
    {
        //Get current project
        $project = Project::where('id', $id)->first();

        //Get cards from current project
        $card_ids = Card::where('project_id', $id)->pluck('id')->toArray();

        //Set sprint start and end date
        $date = Carbon::now();
        $date->setWeekStartsAt($project['sprint_start_day']);
        $date->setWeekEndsAt(
            $project['sprint_start_day'] == 0 ? 6 : $project['sprint_start_day'] -1
        );
        $start_date = new Carbon($date->startOfWeek());
        $end_date = new Carbon($date->startOfWeek()->addDays($project['sprint_duration'])->subMicrosecond());

        //Get the starting card logs
        //Why not go stratight to get the id? Idk maybe the logs will be usefull, we'll see
        $starting_card_logs = CardLog::whereIn('card_id', $card_ids)
            ->whereIn('state',['planned','started','finished','accepted','rejected'])
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('card_id')
            ->get()->toArray();

        //Get the starting card ids
        $starting_cards_ids = array_column($starting_card_logs, 'card_id');
        
        //Get the total points at the start of the sprint
        $starting_cards_points = array_sum(Card::whereIn('id', $starting_cards_ids)->pluck('points')->toArray());

        $perfect_burndown = array();

        $sprint_card_points = array();
        $sprint_card_ids = array();

        //Get total unfinished card points every day on the duration of the sprint and calculate perfect burndown
        for ($i=0; $i < $project['sprint_duration']; $i++) {

            //Get the perfect burndown point and push it to array
            $perfect_burndown_point = $starting_cards_points - ($starting_cards_points * $i / ($project['sprint_duration'] - 1));
            
            //Pushing the perfect burndown to array, still on the fence of rounding it or not
            //array_push($perfect_burndown, intval(round($perfect_burndown_point)));
            array_push($perfect_burndown, round($perfect_burndown_point, 2));

            //Get the start date + i cards logs
            //Only take cards that are planned, started, finished, accepted, and rejected
            $todays_card_logs = CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['planned','started','finished','accepted','rejected'])
                ->whereBetween('created_at', [new Carbon($start_date), new Carbon($start_date->addDay())])
                ->groupBy('card_id')
                ->get()->toArray();
            
            //Get cards ids that have activity today
            $newly_added_cards_ids = array_column($todays_card_logs, 'card_id');

            //Compare with previous day card ids and add new cards if not exist
            $todays_card_ids = $i == 0 ? $newly_added_cards_ids : array_unique(array_merge($sprint_card_ids[$i-1], $newly_added_cards_ids));
            
            //Get released card logs
            $todays_released_card_logs = CardLog::whereIn('card_id', $card_ids)
                ->whereIn('state',['released'])
                ->whereBetween('created_at', [new Carbon($start_date->subDay()), new Carbon($start_date->addDay())])
                ->groupBy('card_id')
                ->get()->toArray();

            //Get released card ids
            $todays_released_card_logs = array_column($todays_released_card_logs, 'card_id');

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
        
        dd($sprint_card_points, $sprint_card_ids, $starting_cards_points, $perfect_burndown);
    }
}
