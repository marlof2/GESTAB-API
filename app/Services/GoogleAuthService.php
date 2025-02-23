<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\GoogleCalendarToken;
use App\Models\User;
use Google_Client;
use Illuminate\Support\Facades\Log;
use Exception;

final class GoogleAuthService
{
    private Google_Client $client;

    public function __construct()
    {
        $this->client = new Google_Client();

        // Adiciona a configuração do cliente HTTP para resolver o erro SSL
        $this->client->setHttpClient(
            new \GuzzleHttp\Client([
                'verify' => false, // ⚠️ Use apenas em desenvolvimento
                'timeout' => 10
            ])
        );

        $this->configureClient();
    }

    private function configureClient(): void
    {
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setScopes([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.events'
        ]);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setIncludeGrantedScopes(true);
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback(string $authCode, User $user): array
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($token['error'])) {
                throw new Exception('Erro ao obter token: ' . $token['error']);
            }

            // Salva ou atualiza o token no banco
            GoogleCalendarToken::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'access_token' => $token['access_token'],
                    'refresh_token' => $token['refresh_token'] ?? null,
                    'token_type' => $token['token_type'],
                    'expires_at' => now()->addSeconds($token['expires_in'])
                ]
            );

            return [
                'success' => true,
                'message' => 'Autenticação realizada com sucesso'
            ];

        } catch (Exception $e) {
            Log::error('Erro na autenticação Google:', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);

            return [
                'success' => false,
                'message' => 'Erro na autenticação: ' . $e->getMessage()
            ];
        }
    }

    public function refreshToken(GoogleCalendarToken $token): array
    {
        try {
            $this->client->setAccessToken([
                'access_token' => $token->access_token,
                'refresh_token' => $token->refresh_token,
                'token_type' => $token->token_type,
                'expires_in' => $token->expires_at->timestamp - now()->timestamp
            ]);

            if ($this->client->isAccessTokenExpired()) {
                $newToken = $this->client->fetchAccessTokenWithRefreshToken($token->refresh_token);

                $token->update([
                    'access_token' => $newToken['access_token'],
                    'expires_at' => now()->addSeconds($newToken['expires_in'])
                ]);

                return [
                    'success' => true,
                    'access_token' => $newToken['access_token']
                ];
            }

            return [
                'success' => true,
                'access_token' => $token->access_token
            ];

        } catch (Exception $e) {
            Log::error('Erro ao atualizar token:', [
                'error' => $e->getMessage(),
                'token_id' => $token->id
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao atualizar token: ' . $e->getMessage()
            ];
        }
    }
}

