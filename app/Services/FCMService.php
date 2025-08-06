<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use function Laravel\Prompts\alert;

class FCMService
{
    protected $projectId;
    protected $clientEmail;
    protected $privateKey;

    public function __construct()
    {
        $this->projectId = config('fcm.project_id');
        $this->clientEmail = config('fcm.client_email');
        $this->privateKey = str_replace("\\n", "\n", config('fcm.private_key'));
    }

    protected function getAccessToken()
    {
        $now = time();
        $payload = [
            'iss' => $this->clientEmail,
            'sub' => $this->clientEmail,
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
        ];

        $jwt = \Firebase\JWT\JWT::encode($payload, $this->privateKey, 'RS256');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        return $response->json('access_token');
    }




    public function sendNotification($fcmToken, $title, $body)
    {
        $accessToken = $this->getAccessToken();

        

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'webpush' => [
                    'notification' => [
                        'title' => $title,  
                        'body' => $body,

                    ]
                ]
            ]
        ];

        $response = Http::withToken($accessToken)
            ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", $payload);

        \Log::info('FCM RESPONSE', $response->json());

        return $response->json();
    }



}
