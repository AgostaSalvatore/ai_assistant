<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateContentRequest;
use OpenAI;

class AiGeneratorController extends Controller
{
    public function store(GenerateContentRequest $request)
    {
        $data = $request->validated();

        $lengthMap = [
            'breve' => 'circa 120-180 parole',
            'medio' => 'circa 250-350 parole',
            'lungo' => 'circa 600-900 parole',
        ];

        $lengthHint = $lengthMap[$data['length']] ?? 'lunghezza media';

        $prompt = <<<PROMPT
            Genera contenuti in formato JSON valido, senza testo extra.

            Tema: {$data['topic']}
            Tono: {$data['tone']}
            Lingua: {$data['language']}
            Lunghezza: {$lengthHint}

            Devi restituire ESATTAMENTE questo JSON:
            {
              "blog_article": "string",
              "linkedin_post": "string",
              "hashtags": ["#tag1", "#tag2", "#tag3", "#tag4", "#tag5", "#tag6", "#tag7", "#tag8"]
            }

            Regole:
            - blog_article: un articolo coerente e ben formattato (ma sempre stringa)
            - linkedin_post: breve, con hook iniziale e call-to-action
            - hashtags: max 8, tutte con #
            PROMPT;

        try {
            $client = OpenAI::client(config('services.openai.key'));

            $response = $client->chat()->create([
                'model'       => 'gpt-4o-mini',
                'messages'    => [
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

            $content = $response->choices[0]->message->content ?? '';

            $json = json_decode($content, true);

            if (!is_array($json) || !isset($json['blog_article'], $json['linkedin_post'], $json['hashtags'])) {
                // fallback: se il modello non ha restituito JSON pulito
                return response()->json([
                    'input' => $data,
                    'raw'   => $content,
                    'error' => 'Risposta AI non in JSON valido',
                ], 502);
            }

            return response()->json([
                'input'         => $data,
                'blog_article'  => $json['blog_article'],
                'linkedin_post' => $json['linkedin_post'],
                'hashtags'      => $json['hashtags'],
            ]);
        } catch (\Throwable $e) {
            $msg = $e->getMessage();

            if (str_contains($msg, 'rate limit')) {
                return response()->json([
                    'error'   => 'Rate limit',
                    'message' => 'Hai fatto troppe richieste ravvicinate. Aspetta 10-20 secondi e riprova.',
                ], 429);
            }

            return response()->json([
                'error'   => 'Errore chiamata OpenAI',
                'message' => $msg,
            ], 500);
        }
    }
}
