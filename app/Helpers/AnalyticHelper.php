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
        return $sprint_dates;
    }

    public function getCurrentDayOfSprint($project_id){
        $project = Project::where('id', $project_id)->first();
        $current_day = 0;
        
        $sprint_date = $this->getProjectSprintDates($project_id, 1)[0];
        $start_date = new Carbon($sprint_date[0]);

        for ($i=0; $i < $project['sprint_duration']; $i++) {
            if($start_date->eq(Carbon::createMidnightDate(Carbon::now()->year, Carbon::now()->month, Carbon::now()->day))){
                $current_day = $i;
                break;
            }
            $start_date->addDay();
        }
        
        return $current_day + 1;
    }

    public function getProjectCurrentSprintDates($project_id){
        $sprint_dates = array();
        $current_sprint_start_date = $this->getProjectSprintDates($project_id, 1)[0][0];

        //Get the project
        $project = Project::where('id', $project_id)->first();
        
        for ($i=0; $i < $project['sprint_duration']; $i++) {
            $dates = [];
            array_push($dates, new Carbon($current_sprint_start_date));
            $current_sprint_start_date->addDay();
            array_push($dates, new Carbon($current_sprint_start_date));
            array_push($sprint_dates, $dates);
        }
        return $sprint_dates;
    }

    public function convertArrayStringToArrayInt($array){
        foreach ($array as $key => $value) {
            $array[$key] = (int)$value;
        }

        return $array;
    }

    public function getCardLogs($card_id, $states, $dates){
        $card_logs = CardLog::where('card_id', $card_id)
            ->whereIn('state', $states)
            ->whereBetween('created_at', $dates)
            ->get()->toArray();
        
        return $card_logs;
    }

    public function getCardsIds($card_ids, $states, $dates){
        $cards = array_column(CardLog::whereIn('card_id', $card_ids)
            ->whereIn('state', $states)
            ->whereBetween('created_at', $dates)
            ->groupBy('card_id')
            ->get()->toArray(), 'card_id');

        return $cards;
    }

    public function formatCardTimelineData($card_info, $card_log, $end_date){
        $name = '#' . $card_info['id'] . ' - ' . $card_info['title'];
        $state = ucfirst(strtolower($card_log['state']));
        $colour = $this->getCardStateColour($card_log['state']);
        $start = new Carbon($card_log['created_at']);
        $end = new Carbon($end_date);
        // $start = json_encode(date($card_log['created_at']));
        // $end = json_encode(date($end_date));
        $data = [$name, $state, $colour, $start, $end];
        return $data;
    }

    public function getCardStateColour($state){
        switch (ucfirst(strtolower($state))) {
            case 'Planned': 
                return "#dbdbdb";
                break;
            case 'Started': 
                return "#f08000";
                break;
            case 'Finished': 
                return "#203e64";
                break;
            case 'Accepted': 
                return "#629200";
                break;
            case 'Rejected': 
                return "#a71f39";
                break;
            default:
                return "#dbdbdb";
                break;
        }
    }
}
