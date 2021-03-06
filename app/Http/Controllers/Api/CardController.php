<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiGithubHelper;
use App\Helpers\CardHelper;
use App\Http\Controllers\Controller;
use App\Model\Card;
use App\Model\CardLog;
use App\Model\Project;
use App\Model\ProjectLog;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($project_id)
    {
        $cards = Card::with(['project' => function ($query) use ($project_id) {
            $query->where('id', $project_id);
        }])
            ->where('project_id', $project_id)
            ->get();

        foreach ($cards as $card) {
            $cardState = CardLog::where('card_id', $card->id)->latest()->first()->state;
            $card->state = ucfirst($cardState);
        }

        return $cards;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $project_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required|max:255',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $randomedId = CardHelper::randomNumber();

        try {
            $card = Card::create([
                'id' => $randomedId,
                'title' => $request->title,
                'project_id' => $project_id,
                'owner' => $request->owner,
                'requester' => $user->id,
                'github_branch_name' => null,
                'description' => $request->description,
                'points' => $request->points,
                'type' => strtolower($request->type)
            ]);

            $card->state = 'Created';

            ProjectLog::create([
                'user_id' => $user->id,
                'project_id' => $project_id,
                'log' => $user->name . ' created a card with title ' . "'" . $card->title . "'" . ' at ' . $card->created_at->format('Y-m-d H:i:s')
            ]);

            CardLog::create([
                'card_id' => $card->id,
                'state' => 'created'
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }

        return response()->json(['success' => true, 'card' => $card], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $card_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $card = Card::find($card_id);
        if (is_null($card)) {
            return abort(404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required|max:255',
            'points' => 'required|numeric',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        try {
            $card->title = $request->title;
            $card->github_branch_name = $request->github_branch_name;
            $card->owner = $request->owner;
            $card->requester = $user->id;
            $card->description = $request->description;
            $card->points = $request->points;
            $card->type = $request->type;
            $card->save();

            ProjectLog::create([
                'user_id' => $user->id,
                'project_id' => $project_id,
                'log' => $user->name . ' updated a card with title ' . "'" . $card->title . "'" . ' at ' . $card->created_at->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }


        return response()->json(['success' => true, 'data' => $card], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $card_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $card = Card::find($card_id);

        try {
            ProjectLog::create([
                'user_id' => $user->id,
                'project_id' => $project_id,
                'log' => $user->name . ' deleted a card with title ' . "'" . $card->title . "'" . ' at ' . $card->created_at->format('Y-m-d H:i:s')
            ]);

            $card->delete();
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }

        return response()->json(['success' => true], 204);
    }

    public function createGithubBranch(Request $request, $project_id, $card_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::find($project_id);
        $card = Card::find($card_id);

        $validator = Validator::make($request->all(), [
            'branch_name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $branchName = $card->id . '-' . $request->branch_name;

        $finalBranchName = ApiGithubHelper::createGithubBranch($user, $project, $branchName);

        try {
            $card->github_branch_name = $finalBranchName;
            $card->save();

            ProjectLog::create([
                'user_id' => $user->id,
                'project_id' => $project_id,
                'log' => $user->name . ' create a branch ' . $finalBranchName . 'in card ' . "'" . $card->title . "'" . ' at ' . $card->created_at->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }

        return response()->json(['success' => 'Branch ' . $finalBranchName . ' has successfully created.', 'data' => $finalBranchName], 201);
    }

    public function updateCardState(Request $request, $project_id, $card_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $card = Card::find($card_id);

        try {
            CardLog::create([
                'card_id' => $card->id,
                'state' => strtolower($request->state)
            ]);
            $card->state = strtolower($request->state);

            ProjectLog::create([
                'user_id' => $user->id,
                'project_id' => $project_id,
                'log' => $user->name . ' has change state of card ' . "'" . $card->title . "'" . ' to ' . $request->state . ' at ' . $card->created_at->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 422);
        }

        return response()->json(['success' => 'State updated', 'data' => $card], 204);
    }

    public function showCardReadyToRelease($project_id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $cards = Card::where('project_id', $project_id)->get();

        $acceptedCards = $cards->filter(function ($card) {
            $cardLog = CardLog::where('card_id', $card->id)->latest()->first();

            if (isset($cardLog->state)) {
                $state = $cardLog->state;

                $requester = User::find($card->requester)->name;
                $owner = User::find($card->owner)->name;

                $card->requester = $requester;
                $card->owner = $owner;
                $card->state = $state;

                return $state === 'accepted';
            }
            return [];
        })->values()->all();

        return response()->json($acceptedCards, 200);
    }
}
