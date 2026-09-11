<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FatSecretService;
use App\Models\Food;

class ImportFatSecretFoods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fatsecret:import {query} {--page=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import foods from FatSecret by search query';

    /**
     * Execute the console command.
     */
    public function handle(FatSecretService $fatSecret)
    {
        $query = $this->argument('query');
        $page = (int) $this->option('page');

        $this->info("Buscando '{$query}' en FatSecret (Página {$page})...");

        try {
            $foods = $fatSecret->searchFoods($query, $page);

            if (empty($foods)) {
                $this->warn('No se encontraron alimentos.');
                return;
            }

            foreach ($foods as $foodData) {
                $desc = $foodData['food_description'] ?? '';
                
                $calories = 0;
                $fat = 0;
                $carbs = 0;
                $protein = 0;

                if (preg_match('/Calories:\s*([\d\.]+)kcal/i', $desc, $m)) $calories = (float) $m[1];
                if (preg_match('/Fat:\s*([\d\.]+)g/i', $desc, $m)) $fat = (float) $m[1];
                if (preg_match('/Carbs:\s*([\d\.]+)g/i', $desc, $m)) $carbs = (float) $m[1];
                if (preg_match('/Protein:\s*([\d\.]+)g/i', $desc, $m)) $protein = (float) $m[1];

                $food = Food::updateOrCreate(
                    ['fatsecret_id' => $foodData['food_id']],
                    [
                        'name' => $foodData['food_name'],
                        'calories' => $calories,
                        'fat' => $fat,
                        'carbs' => $carbs,
                        'protein' => $protein,
                        'serving_description' => $desc,
                    ]
                );

                $this->line("Importado: {$food->name} ({$calories} kcal, P: {$protein}g, C: {$carbs}g, G: {$fat}g)");
            }

            $this->info('¡Importación completada!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
