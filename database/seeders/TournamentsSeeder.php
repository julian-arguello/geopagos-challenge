<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\Tournament;
use App\Models\TournamentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TournamentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tournament::create([
            'gender_id' => Gender::MALE_ID,
            'status_id' => TournamentStatus::PLAYABLE
        ]);

        Tournament::create([
            'gender_id' => Gender::FEMALE_ID,
            'status_id' => TournamentStatus::PLAYABLE
        ]);
    }
}
