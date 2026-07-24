<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\JsonResponse;

class InterestController extends Controller
{
    public function index(): JsonResponse
    {
        $interests = Interest::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'icon']);

        return response()->json(['data' => $interests]);
    }
}
