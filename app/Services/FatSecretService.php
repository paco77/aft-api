<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FatSecretService
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl = 'https://oauth.fatsecret.com/connect/token';
    private $apiUrl = 'https://platform.fatsecret.com/rest/server.api';

    public function __construct()
    {
        $this->clientId = config('services.fatsecret.client_id');
        $this->clientSecret = config('services.fatsecret.client_secret');
    }

    /**
     * Get OAuth 2.0 Access Token
     */
    private function getAccessToken()
    {
        // Cache the token to avoid hitting the rate limit
        return Cache::remember('fatsecret_access_token', 86000, function () {
            $response = Http::asForm()->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($this->tokenUrl, [
                'grant_type' => 'client_credentials',
                'scope' => 'basic'
            ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            throw new \Exception('Failed to authenticate with FatSecret API: ' . $response->body());
        });
    }

    /**
     * Search foods by query string
     */
    public function searchFoods($query, $pageNumber = 0)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get($this->apiUrl, [
            'method' => 'foods.search',
            'search_expression' => $query,
            'format' => 'json',
            'page_number' => $pageNumber,
            'max_results' => 20
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['error'])) {
                $errorMsg = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown API error';
                throw new \Exception('FatSecret SDK Error: ' . $errorMsg);
            }
            
            if (!isset($data['foods']) || !isset($data['foods']['food'])) {
                return [];
            }
            
            return $data['foods']['food'];
        }

        throw new \Exception('Failed to search foods: ' . $response->body());
    }

    /**
     * Get food details by ID
     */
    public function getFood($foodId)
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)
            ->get($this->apiUrl, [
            'method' => 'food.get.v2',
            'food_id' => $foodId,
            'format' => 'json'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['error'])) {
                $errorMsg = isset($data['error']['message']) ? $data['error']['message'] : 'Unknown API error';
                throw new \Exception('FatSecret SDK Error: ' . $errorMsg);
            }
            
            return isset($data['food']) ? $data['food'] : null;
        }

        throw new \Exception('Failed to get food details: ' . $response->body());
    }
}
