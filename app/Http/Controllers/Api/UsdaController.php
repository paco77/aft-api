<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UsdaService;
use Illuminate\Http\Request;

class UsdaController extends Controller
{
    private $usdaService;

    public function __construct(UsdaService $usdaService)
    {
        $this->usdaService = $usdaService;
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $page = $request->query('page', 1);

        if (!$query) {
            return response()->json(['message' => 'Query parameter is required'], 400);
        }

        try {
            $foods = $this->usdaService->searchFoods($query, $page);
            return response()->json($foods);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
