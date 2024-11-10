<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;

final class ForgotPasswordController extends Controller
{
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Email de recuperação enviado com sucesso!'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Não foi possível enviar o email de recuperação.'
        ], 400);
    }
}
