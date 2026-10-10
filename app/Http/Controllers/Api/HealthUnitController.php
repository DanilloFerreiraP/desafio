<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HealthUnit;
use Illuminate\Http\JsonResponse;

class HealthUnitController extends Controller
{
    public function index(): JsonResponse
    {
        $units = HealthUnit::query()
            ->select('id', 'name', 'city')
            ->orderBy('name')
            ->get();

        return response()->json($units);
    }
}
