<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;
use Illuminate\Support\Facades\Log;
use Exception;

final class GoogleLoginService
{
    private GoogleClient $client;

    public function __construct()
    {
        $this->client = new GoogleClient();

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
            'openid',
            'email',
            'profile'
        ]);
        $this->client->setAccessType('online');
    }

    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    public function handleCallback(string $authCode): array
    {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($token['error'])) {
                throw new Exception('Erro ao obter token: ' . $token['error']);
            }

            $oauth2 = new Oauth2($this->client);
            $googleUser = $oauth2->userinfo->get();
            return [
                'success' => true,
                'user' => [
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'picture' => $googleUser->getPicture()
                ]
            ];

        } catch (Exception $e) {
            Log::error('Erro na autenticação Google:', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Erro na autenticação: ' . $e->getMessage()
            ];
        }
    }
}
