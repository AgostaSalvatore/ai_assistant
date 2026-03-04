<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateContentRequest;

class AiGeneratorController extends Controller
{
    public function store(GenerateContentRequest $request)
    {
        $data = $request->validated();

        return response()->json([
            'input'         => $data,
            'blog_article'  => "Articolo (mock) su: {$data['topic']} - tono {$data['tone']} - lingua {$data['language']} - lunghezza {$data['length']}",
            'linkedin_post' => "Post LinkedIn (mock) su: {$data['topic']}",
            'hashtags'      => ['#laravel', '#react', '#ai'],
        ]);
    }
}
