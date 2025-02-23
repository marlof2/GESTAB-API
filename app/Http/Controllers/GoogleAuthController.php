<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\GoogleAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class GoogleAuthController extends Controller
{
    public function __construct(
        private readonly GoogleAuthService $googleAuthService
    ) {}

    public function redirect(): JsonResponse
    {
        $authUrl = $this->googleAuthService->getAuthUrl();

        return response()->json([
            'auth_url' => $authUrl
        ]);
    }

    public function callback(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $result = $this->googleAuthService->handleCallback($request->code, Auth::user());

        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 400);
        }

        return response()->json([
            'message' => 'Google conectado com sucesso'
        ]);
    }
}
