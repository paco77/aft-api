<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StepLogController extends Controller
{
    public function index()
    {
        $logs = \App\Models\UserStepLog::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'steps' => 'required|integer|min:0',
            'date' => 'nullable|date'
        ]);

        $date = $request->date ?? date('Y-m-d');

        $log = \App\Models\UserStepLog::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'date' => $date
            ],
            [
                'steps' => $request->steps
            ]
        );

        return response()->json(['message' => 'Pasos guardados correctamente', 'log' => $log]);
    }
}
