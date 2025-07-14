<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class HubApiService
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
            'headers' => [
                'Accept' => 'application/json',
            ],
            'verify' => false, // gunakan false hanya di local/dev
        ]);
    }

    public function getAccessToken()
    {
        try {
            $response = $this->client->post('oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => '*',
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            return $data['access_token'];
        } catch (\Exception $e) {
            Log::error('Gagal mendapatkan token dari Hub: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateProductVisibility($productId, array $data)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->put("/api/products/{$productId}/visibility", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'json' => $data,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error update visibilitas produk di Hub: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createProduct($data)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->post("/api/products/sync", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
                'json' => $data,
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error membuat produk di Hub: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct($productId)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->delete("products/{$productId}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (\Exception $e) {
            Log::error('Error menghapus produk dari Hub: ' . $e->getMessage());
            throw $e;
        }
    }
}
