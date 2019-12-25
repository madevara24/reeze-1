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

        return 'estimation';
    }
}
