<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentStatus;
use App\Services\TournamentService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{

    protected $tournamentService;

    public function __construct(TournamentService $tournamentService)
    {

        $this->tournamentService  = $tournamentService;
    }

    public function index()
    {
        $tournaments = Tournament::all();
        return view('tournaments.index', compact('tournaments'));
    }

    public function show($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournamentStatus = TournamentStatus::getTournamentStatusOptions();
        return view('tournaments.show', compact('tournament', 'tournamentStatus'));
    }

    public function play(Request $request)
    {
        $tournamentId = (int) $request->input('tournament_id');
        $tournament = Tournament::findOrFail($tournamentId);
        $tournamentResult = $this->tournamentService->run($tournament);
        return redirect()->route('tournament.show', ['id' => $tournament->id])
            ->with([
                'status' => $tournamentResult->status,
                'message' => $tournamentResult->message
            ]);
    }

    public function result($id)
    {
        $tournament = Tournament::findOrFail($id);
        $tournamentStatus = TournamentStatus::getTournamentStatusOptions();

        return view('tournaments.result', compact('tournament', 'tournamentStatus',));
    }
}
