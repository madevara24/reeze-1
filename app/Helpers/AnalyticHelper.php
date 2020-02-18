<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Model\Project;
use App\Model\Card;
use App\Model\CardLog;

class AnalyticHelper{
    
    public function getProjectSprintDates($project_id, $number_of_sprints = 5){

        $sprint_dates = array();

        //Get the project
        $project = Project::where('id', $project_id)->first();

        //Set fisrt sprint start and end date
        $date = new Carbon($project['created_at']);
        $date->setWeekStartsAt($project['sprint_start_day']);
        $date->setWeekEndsAt(
            $project['sprint_start_day'] == 0 ? 6 : $project['sprint_start_day'] -1
        );
        $date->startOfWeek();

        //Get all the sprint start and end date until current sprint
        while(true){
            $sprint_start_date = new Carbon($date);
            $sprint_end_date = new Carbon($date->addDays($project['sprint_duration']));

            array_push($sprint_dates, array($sprint_start_date, $sprint_end_date));

            if(Carbon::now()->between($sprint_start_date, $sprint_end_date)){
                break;
            }
        }

        $sprint_dates = array_reverse($sprint_dates);
        
        //Cut the array to only get the last 5 iteration
        while (count($sprint_dates) > $number_of_sprints) {
            array_pop($sprint_dates);
        }

        $sprint_dates = array_reverse($sprint_dates);
        //dd($sprint_dates);
        return $sprint_dates;
    }

    public function convertArrayStringToArrayInt($array){
        foreach ($array as $key => $value) {
            $array[$key] = (int)$value;
        }

        return $array;
    }

    public function getCardsIds($card_ids, $states, $dates){
        $cards = array_column(CardLog::whereIn('card_id', $card_ids)
        ->whereIn('state', $states)
        ->whereBetween('created_at', $dates)
        ->groupBy('card_id')
        ->get()->toArray(), 'card_id');

        return $cards;
    }
}
