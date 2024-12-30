<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gender::create([
            'code' => 'Male',
            'title' => 'Masculino',
        ]);

        Gender::create([
            'code' => 'Female',
            'title' => 'Femenino',
        ]);
    }
}
