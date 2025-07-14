<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HubCategoryService
{
    protected $client;
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->baseUrl = env('HUB_API_URL');
        $this->clientId = env('HUB_CLIENT_ID');
        $this->clientSecret = env('HUB_CLIENT_SECRET');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => ['Accept' => 'application/json'],
            'verify' => false,
        ]);
    }

    protected function getAccessToken()
    {
        $response = $this->client->post('/api/oauth/token', [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'scope' => '*',
            ],
        ]);

        $data = json_decode((string) $response->getBody(), true);
        return $data['access_token'];
    }

    public function createCategory($data)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post('product-category/sync', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'json' => $data,
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
