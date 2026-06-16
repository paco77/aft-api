<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Administrador
        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'admin@app.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Coach
        $coach = User::factory()->create([
            'name' => 'Coach Principal',
            'username' => 'coach',
            'email' => 'coach@app.com',
            'password' => bcrypt('password'),
            'role' => 'coach',
            'experience_years' => 5,
            'training_info' => 'Certificado en Entrenamiento Personal e Hipertrofia.',
        ]);

        // 3. Clientes asignados al Coach
        for ($i = 1; $i <= 3; $i++) {
            User::factory()->create([
                'name' => "Cliente $i",
                'username' => "cliente$i",
                'email' => "cliente$i@app.com",
                'password' => bcrypt('password'),
                'role' => 'client',
                'coach_id' => $coach->id,
                'age' => 25 + $i,
                'weight' => 70 + ($i * 2),
                'height' => 1.70 + ($i * 0.05),
                'objectives' => 'Ganar masa muscular y mejorar salud',
            ]);
        }

        $this->call([
            MuscleGroupSeeder::class ,
            ExerciseLibrarySeeder::class ,
        ]);
    }
}
