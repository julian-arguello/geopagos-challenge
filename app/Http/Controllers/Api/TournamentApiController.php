<?php

namespace App\Http\Controllers\Api;

use App\Models\Gender;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTournamentRequest;
use App\Models\MalePlayer;
use App\Models\TournamentStatus;
use App\Services\TournamentService;

class TournamentApiController extends ApiController
{

    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {
        $this->tournamentService  = $tournamentService;
    }

    public function index(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'gender' => 'nullable|string|in:MALE,FEMALE',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'El valor de gender no es válido. Se espera "MALE" o "FEMALE".',
                422
            );
        }

        $genderKey = $request->query('gender');
        $gender = $genderKey
            ? [Gender::getGenderOptions()[$genderKey]]
            : [Gender::MALE_ID, Gender::FEMALE_ID];

        $tournaments = Tournament::with('status', 'gender')
            ->whereIn('gender_id', $gender)
            ->get();

        return $this->successResponse(
            $tournaments
        );
    }

    public function show($id)
    {
        $tournament = Tournament::with('status', 'players')->find($id);

        if (is_null($tournament)) {
            return $this->errorResponse('No existe un torneo con el ID proporcionado.');
        }

        return $this->successResponse(
            $tournament
        );
    }

    public function store(StoreTournamentRequest $request)
    {

        $data = $request->validated();

        $genderKey = $data['tournament_gender'];
        $genderId = Gender::getGenderOptions()[$genderKey];
        $tournament = Tournament::create([
            'gender_id' => $genderId,
            'status_id' => TournamentStatus::PLAYABLE,
        ]);

        foreach ($data['players'] as $player) {
            if ($genderId === Gender::MALE_ID) {
                MalePlayer::createAndAssignToTournament($player, $tournament);
            } else {
                MalePlayer::createAndAssignToTournament($player, $tournament);
            }
        };

        $tournamentResult = $this->tournamentService->run($tournament);

        return response()->json([
            'message' => 'Torneo jugado exitosamente',
            'data' => $tournamentResult
        ]);
    }
}
