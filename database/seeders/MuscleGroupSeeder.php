<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MuscleGroup;
use Illuminate\Support\Str;

class MuscleGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            ['name' => 'Pecho', 'color' => '#FF4757'],
            ['name' => 'Espalda', 'color' => '#2ED573'],
            ['name' => 'Cuádriceps', 'color' => '#70A1FF'],
            ['name' => 'Isquios', 'color' => '#3742FA'],
            ['name' => 'Glúteos', 'color' => '#FF6348'],
            ['name' => 'Hombros', 'color' => '#ECCC68'],
            ['name' => 'Bícep', 'color' => '#A29BFE'],
            ['name' => 'Trícep', 'color' => '#FF6B81'],
            ['name' => 'Core', 'color' => '#FFA502'],
            ['name' => 'Descanso', 'color' => '#57606F'],
        ];

        foreach ($groups as $group) {
            MuscleGroup::updateOrCreate(
            ['slug' => Str::slug($group['name'])],
            [
                'name' => $group['name'],
                'color' => $group['color']
            ]
            );
        }
    }
}
