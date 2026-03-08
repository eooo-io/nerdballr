<?php

namespace App\Services;

use App\Models\Concept;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class AiService
{
    private const TOKEN_CAP = 4000;

    private const MAX_RESPONSE_TOKENS = 600;

    private const SYSTEM_PROMPT = <<<'PROMPT'
You are a football tactics instructor embedded in the Football Field Intelligence System.

You have access to a structured library of football concepts. Each concept includes:
- a label and category
- a plain-text tactical explanation
- information about counters and related concepts

RULES:
1. Only explain tactics using the concepts provided in the context block below.
2. Never invent formations, coverages, or route names not present in the context.
3. If the user asks about something not in the context, say so clearly and suggest which concept category might help.
4. Use plain, direct language. Avoid excessive jargon unless the user demonstrates familiarity.
5. When comparing two concepts, structure your response as: Offense advantage / Defense advantage / Key read.
6. Never describe play outcomes or game results. Describe spatial and timing mechanics only.
PROMPT;

    // Order matters: more specific patterns must come before general ones
    private const INTENT_PATTERNS = [
        'pre-snap-read' => '/\b(what should.*(see|read|look)|pre.?snap|what does.*tell)\b/i',
        'recommend' => '/\b(what should I (study|learn|look at)|recommend|suggest|next)\b/i',
        'compare' => '/\b(vs\.?|versus|compare|difference between|differ)\b/i',
        'counter' => '/\b(how (do you |to )?beat|what stops|counter|exploit|weakness)\b/i',
        'explain' => '/\b(what is|explain|how does|how do|tell me about|describe)\b/i',
    ];

    public function query(string $userQuery, array $conceptSlugs): array
    {
        $concepts = $this->retrieveConcepts($conceptSlugs);

        if ($concepts->isEmpty()) {
            return [
                'response' => 'I don\'t have any matching concepts in my library to answer that question. Try browsing the concept library to find relevant formations, coverages, or route concepts.',
                'intent' => $this->detectIntent($userQuery),
                'concepts_used' => [],
            ];
        }

        $context = $this->buildContext($concepts);
        $intent = $this->detectIntent($userQuery);
        $response = $this->callApi($userQuery, $context);

        return [
            'response' => $response,
            'intent' => $intent,
            'concepts_used' => $concepts->pluck('slug')->all(),
        ];
    }

    public function detectIntent(string $query): string
    {
        foreach (self::INTENT_PATTERNS as $intent => $pattern) {
            if (preg_match($pattern, $query)) {
                return $intent;
            }
        }

        return 'explain';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Concept>
     */
    public function retrieveConcepts(array $slugs): \Illuminate\Database\Eloquent\Collection
    {
        return Concept::whereIn('slug', $slugs)
            ->select(['id', 'slug', 'label', 'category', 'ai_context', 'counters', 'related'])
            ->get();
    }

    public function buildContext(\Illuminate\Support\Collection $concepts): string
    {
        $context = '';
        $estimatedTokens = 0;

        foreach ($concepts as $concept) {
            $block = "--- {$concept->label} [{$concept->category}] ---\n";
            $block .= $concept->ai_context ?? '';

            if (! empty($concept->counters)) {
                $block .= "\nCounters: ".implode(', ', $concept->counters);
            }

            if (! empty($concept->related)) {
                $block .= "\nRelated: ".implode(', ', $concept->related);
            }

            $block .= "\n\n";

            // Rough token estimate: ~4 chars per token
            $blockTokens = (int) ceil(strlen($block) / 4);

            if ($estimatedTokens + $blockTokens > self::TOKEN_CAP) {
                break;
            }

            $context .= $block;
            $estimatedTokens += $blockTokens;
        }

        return $context;
    }

    /**
     * @throws ConnectionException
     */
    public function callApi(string $userQuery, string $context): string
    {
        $systemPrompt = self::SYSTEM_PROMPT."\n\nCONTEXT:\n".$context;

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.api_key'),
            'anthropic-version' => '2023-06-01',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model', 'claude-sonnet-4-20250514'),
            'max_tokens' => self::MAX_RESPONSE_TOKENS,
            'system' => $systemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $userQuery],
            ],
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('AI service unavailable');
        }

        return strip_tags($response->json('content.0.text', 'Unable to generate a response.'));
    }
}
