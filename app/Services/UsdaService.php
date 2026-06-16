<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UsdaService
{
    private $apiKey;
    private $apiUrl = 'https://api.nal.usda.gov/fdc/v1/foods/search';

    public function __construct()
    {
        $this->apiKey = config('services.usda.api_key', 'DEMO_KEY');
    }

    /**
     * Search foods by query string using USDA FoodData Central
     */
    public function searchFoods($query, $pageNumber = 1)
    {
        $response = Http::get($this->apiUrl, [
            'api_key' => $this->apiKey,
            'query' => $query,
            'pageSize' => 20,
            'pageNumber' => $pageNumber
        ]);

        if ($response->successful()) {
            return $response->json('foods');
        }

        throw new \Exception('Failed to search foods in USDA API: ' . $response->body());
    }
}
