<?php

namespace App\Http\Controllers;

use App\Http\Requests\AiQueryRequest;
use App\Models\AiSession;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AiController extends Controller
{
    private const MAX_SESSION_MESSAGES = 100;

    public function __construct(
        private readonly AiService $aiService,
    ) {}

    public function query(AiQueryRequest $request): JsonResponse
    {
        $rateLimitResult = $this->checkRateLimit($request);

        if ($rateLimitResult !== null) {
            return $rateLimitResult;
        }

        $result = $this->aiService->query(
            $request->validated('query'),
            $request->validated('concept_slugs'),
        );

        $session = $this->persistSession($request, $result);

        $remaining = $this->getRemainingQuota($request);

        return response()->json([
            'data' => $result,
            'session_key' => $session->session_key,
        ], 200, [
            'X-RateLimit-Remaining' => $remaining,
        ]);
    }

    public function session(Request $request, string $key): JsonResponse
    {
        $query = AiSession::where('session_key', $key);

        if ($request->user()) {
            $query->where('user_id', $request->user()->id);
        } else {
            $query->whereNull('user_id');
        }

        $session = $query->firstOrFail();

        return response()->json([
            'data' => [
                'session_key' => $session->session_key,
                'messages' => $session->messages,
                'concept_ids' => $session->concept_ids,
            ],
        ]);
    }

    private function checkRateLimit(AiQueryRequest $request): ?JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $key = "ai_query:user:{$user->id}";
            $maxAttempts = 20;
        } else {
            // Always rate limit by IP for guests to prevent bypass via rotating session keys
            $key = "ai_query:guest_ip:{$request->ip()}";
            $maxAttempts = 5;
        }

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return response()->json([
                'message' => 'AI query rate limit exceeded.',
                'retry_after' => $retryAfter,
            ], 429, [
                'Retry-After' => $retryAfter,
                'X-RateLimit-Remaining' => 0,
            ]);
        }

        RateLimiter::hit($key, 3600);

        return null;
    }

    private function getRemainingQuota(AiQueryRequest $request): int
    {
        $user = $request->user();

        if ($user) {
            $key = "ai_query:user:{$user->id}";
            $maxAttempts = 20;
        } else {
            $key = "ai_query:guest_ip:{$request->ip()}";
            $maxAttempts = 5;
        }

        return RateLimiter::remaining($key, $maxAttempts);
    }

    private function persistSession(AiQueryRequest $request, array $result): AiSession
    {
        $user = $request->user();
        $sessionKey = $request->validated('session_key') ?? bin2hex(random_bytes(32));

        $session = AiSession::firstOrCreate(
            ['session_key' => $sessionKey],
            [
                'user_id' => $user?->id,
                'messages' => [],
                'concept_ids' => [],
            ],
        );

        $messages = $session->messages ?? [];
        $messages[] = ['role' => 'user', 'content' => $request->validated('query')];
        $messages[] = ['role' => 'assistant', 'content' => $result['response']];

        // Cap message history to prevent unbounded growth
        if (count($messages) > self::MAX_SESSION_MESSAGES) {
            $messages = array_slice($messages, -self::MAX_SESSION_MESSAGES);
        }

        $conceptIds = array_unique(array_merge(
            $session->concept_ids ?? [],
            $result['concepts_used'],
        ));

        $session->update([
            'messages' => $messages,
            'concept_ids' => array_slice($conceptIds, 0, 50),
        ]);

        return $session;
    }
}
