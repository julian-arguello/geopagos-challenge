<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Player;
use App\Models\PlayerTournament;
use App\Models\Tournament;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerTournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $maleTournament = Tournament::where('gender_id', Gender::MALE_ID)->first();
        $femaleTournament = Tournament::where('gender_id', Gender::FEMALE_ID)->first();

        $malePlayers = Player::whereHas('malePlayer')->get();
        $femalePlayers = Player::whereHas('femalePlayer')->get();

        foreach ($malePlayers as $malePlayer) {
            PlayerTournament::create([
                'player_id' => $malePlayer->id,
                'tournament_id' => $maleTournament->id
            ]);
        }

        foreach ($femalePlayers as $femalePlayer) {
            PlayerTournament::create([
                'player_id' => $femalePlayer->id,
                'tournament_id' => $femaleTournament->id
            ]);
        }
    }
}
