<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

final class ResetPasswordController extends Controller
{
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();


        // Verifica se existe um token válido
        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return response()->json([
                'message' => 'Token inválido ou expirado'
            ], 422);
        }

        // Atualiza a senha do usuário
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Remove o token usado
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'message' => 'Senha atualizada com sucesso'
        ]);
    }
}
