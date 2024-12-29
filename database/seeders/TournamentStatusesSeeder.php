<?php

namespace Database\Seeders;

use App\Models\TournamentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TournamentStatus::create(['code' => 'No Players']);
        TournamentStatus::create(['code' => 'Players Count Must Be a Power of 2']);
        TournamentStatus::create(['code' => 'Playable']);
        TournamentStatus::create(['code' => 'In Progress']);
        TournamentStatus::create(['code' => 'Finished']);
    }
}
