<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\GoogleLoginService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class GoogleLoginController extends Controller
{
    public function __construct(
        private readonly GoogleLoginService $googleService
    ) {}

    public function redirect(): JsonResponse
    {
        return response()->json([
            'auth_url' => $this->googleService->getAuthUrl()
        ]);
    }

    // public function callback(Request $request): JsonResponse
    // {
    //     $request->validate([
    //         'code' => 'required|string'
    //     ]);

    //     $result = $this->googleService->handleCallback($request->code);

    //     if (!$result['success']) {
    //         return response()->json([
    //             'message' => $result['message']
    //         ], 400);
    //     }

    //     // Verifica se o usuário já existe
    //     $existingUser = User::where('email', $result['user']['email'])->first();

    //     $user = $existingUser;
    //     if (!$existingUser) {
    //         // Se não existe, cria um novo usuário
    //         $user = User::create([
    //             'email' => $result['user']['email'],
    //             'name' => $result['user']['name'],
    //             'google_id' => $result['user']['google_id'],
    //             'avatar' => $result['user']['picture']
    //         ]);
    //     }else{
    //         $user->update([
    //             'google_id' => $result['user']['google_id'],
    //             'avatar' => $result['user']['picture']
    //         ]);
    //     }

    //     // Remove tokens antigos
    //     $user->tokens()->delete();

    //     // Inicializa array de abilities
    //     $abilities = [];

    //     // Verifica se o usuário tem perfil e abilities antes de acessá-los
    //     if ($user->profile && $user->profile->abilities) {
    //         foreach ($user->profile->abilities as $ability) {
    //             $abilities[] = $ability->slug;
    //         }
    //     }

    //     $token = $user->createToken('AccessToken', $abilities)->plainTextToken;

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Login realizado com sucesso',
    //         // 'user' => $user,
    //         'needsProfileCompletion' => !$existingUser,
    //         'token' => $token
    //     ], 200);
    // }

    public function callback(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string'
            ]);

            $result = $this->googleService->handleCallback($request->code);

            if (!$result['success']) {
                return redirect('com.marlof2.gestab://signin?error=' . urlencode($result['message']));
            }

            // Verifica se o usuário já existe
            $existingUser = User::where('email', $result['user']['email'])->first();

            $user = $existingUser;
            if (!$existingUser) {
                // Se não existe, cria um novo usuário
                $user = User::create([
                    'email' => $result['user']['email'],
                    'name' => $result['user']['name'],
                    'google_id' => $result['user']['google_id'],
                    'avatar' => $result['user']['picture']
                ]);
            } else {
                $user->update([
                    'google_id' => $result['user']['google_id'],
                    'avatar' => $result['user']['picture']
                ]);
            }

            // Remove tokens antigos
            $user->tokens()->delete();

            // Inicializa array de abilities
            $abilities = [];

            // Verifica se o usuário tem perfil e abilities antes de acessá-los
            if ($user->profile && $user->profile->abilities) {
                foreach ($user->profile->abilities as $ability) {
                    $abilities[] = $ability->slug;
                }
            }

            $token = $user->createToken('AccessToken', $abilities)->plainTextToken;

            // Redireciona para o app
            return redirect('com.marlof2.gestab://SignIn?' . http_build_query([
                'token' => $token,
                'needsProfileCompletion' => !$existingUser,
                'message' => 'Login realizado com sucesso'
            ]));
        } catch (\Exception $e) {
            // Em caso de erro, redireciona com mensagem de erro
            return redirect('com.marlof2.gestab://SignIn?error=' . urlencode($e->getMessage()));
        }
    }
}
