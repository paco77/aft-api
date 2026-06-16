<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\FatSecretService;

class FatSecretController extends Controller
{
    protected $fatSecretService;

    public function __construct(FatSecretService $fatSecretService)
    {
        $this->fatSecretService = $fatSecretService;
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
            'page' => 'nullable|integer|min:0'
        ]);

        try {
            $results = $this->fatSecretService->searchFoods(
                $request->input('query'),
                $request->input('page', 0)
            );

            return response()->json($results);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $food = $this->fatSecretService->getFood($id);
            return response()->json($food);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
