<?php

namespace App\Http\Controllers\Api;

use App\Models\Player;

class PlayerApiController extends ApiController
{

    public function index()
    {
        $femalePlayers = Player::whereHas('femalePlayer')->get();
        $malePlayers = Player::whereHas('malePlayer')->get();

        return $this->successResponse([
            'femalePlayers' => $femalePlayers,
            'malePlayers' => $malePlayers,
        ]);
    }

    public function show($id)
    {
        $player = Player::with(['malePlayer', 'femalePlayer'])
            ->findOrFail($id);

        if (is_null($player)) {
            return $this->errorResponse('No existe un Jugador con el ID proporcionado.');
        }

        return $this->successResponse([
            $player
        ]);
    }
}
