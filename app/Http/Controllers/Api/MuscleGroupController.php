<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MuscleGroup;
use App\Http\Resources\MuscleGroupResource;
use Illuminate\Http\Request;

class MuscleGroupController extends Controller
{
    public function index()
    {
        return MuscleGroupResource::collection(MuscleGroup::all());
    }

    public function show(MuscleGroup $muscleGroup)
    {
        return new MuscleGroupResource($muscleGroup);
    }
}
