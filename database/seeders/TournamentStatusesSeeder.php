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
        TournamentStatus::create([
            'code' => 'No Players',
            'title' => 'Sin jugadores'
        ]);

        TournamentStatus::create([
            'code' => 'Players Count Must Be a Power of 2',
            'title' => 'El número de jugadores debe ser una potencia de 2'
        ]);

        TournamentStatus::create([
            'code' => 'Playable',
            'title' => 'Jugable'
        ]);

        TournamentStatus::create([
            'code' => 'In Progress',
            'title' => 'En progreso'
        ]);

        TournamentStatus::create([
            'code' => 'Finished',
            'title' => 'Finalizado'
        ]);
    }
}
