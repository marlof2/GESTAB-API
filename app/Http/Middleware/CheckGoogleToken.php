<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\GoogleAuthService;
use Closure;
use Illuminate\Http\Request;

final class CheckGoogleToken
{
    public function __construct(
        private readonly GoogleAuthService $googleAuthService
    ) {}

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $token = $user->googleCalendarToken;

        if (!$token) {
            return response()->json([
                'message' => 'Usuário não conectado ao Google Calendar'
            ], 401);
        }
        if ($token->isExpired()) {
            $result = $this->googleAuthService->refreshToken($token);

            if (!$result['success']) {
                return response()->json([
                    'message' => 'Erro ao atualizar token do Google'
                ], 401);
            }
        }

        return $next($request);
    }
}
