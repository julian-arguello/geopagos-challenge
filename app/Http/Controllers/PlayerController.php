<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public function index()
    {



        // Comprobar si la relación no es nula
        $players = Player::with(['malePlayer', 'femalePlayer'])->get();

        foreach ($players as $player) {
            // Comprobar si la relación existe
            if ($player->malePlayer) {
                // Acceder a los atributos de malePlayer
                dd($player->malePlayer);
            }

            if ($player->femalePlayer) {
                // Acceder a los atributos de femalePlayer
                dd($player->femalePlayer->some_attribute);
            } else {
                dd("No female player found for this player.");
            }
        }
    }
}
