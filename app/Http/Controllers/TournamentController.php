<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
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
        return view('tournaments.show', compact('tournament'));
    }

    public function play(Request $request)
    {
        $tournamentId = $request->input('tournament_id');

        $tournament = Tournament::findOrFail($tournamentId);

        //TODO: verificar si si el valor esperado es correcto.

        if ($tournament) {

            $this->tournamentService->run($tournament);

            return view('tournaments.show', compact('tournament'));
        } else {
            dd("TODO: MANEJAR EL ERROR");
        }
    }
}
