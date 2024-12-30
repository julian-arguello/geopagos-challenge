<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{

    public function index()
    {
        $femalePlayers = Player::whereHas('femalePlayer')->get();
        $malePlayers = Player::whereHas('malePlayer')->get();
        $genderOptions = Gender::getGenderOptions();

        return view('players.index', compact('femalePlayers', 'malePlayers', 'genderOptions'));
    }
}
