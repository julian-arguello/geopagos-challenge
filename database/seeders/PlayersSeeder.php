<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\MalePlayer;
use App\Models\FemalePlayer;
use Illuminate\Database\Seeder;

class PlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $players = Player::factory(16)->create();

        $half = ceil($players->count() / 2);

        foreach ($players->take($half) as $player) {
            MalePlayer::factory()->create(['player_id' => $player->id]);
        }

        foreach ($players->skip($half) as $player) {
            FemalePlayer::factory()->create(['player_id' => $player->id]);
        }
    }
}
