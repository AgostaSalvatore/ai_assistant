<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateContentRequest;

class AiGeneratorController extends Controller
{
    public function store(GenerateContentRequest $request)
    {
        return response()->json($request->validated());
    }
}
