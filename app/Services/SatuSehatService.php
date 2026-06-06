<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SatuSehatService
{
    private string $baseUrl;
    private string $authUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->baseUrl     = config('satusehat.base_url', env('SATUSEHAT_BASE_URL', 'https://api-satusehat-stg.dto.kemkes.go.id'));
        $this->authUrl     = config('satusehat.auth_url', env('SATUSEHAT_AUTH_URL', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1'));
        $this->clientId    = config('satusehat.client_id', env('SATUSEHAT_CLIENT_ID', ''));
        $this->clientSecret = config('satusehat.client_secret', env('SATUSEHAT_CLIENT_SECRET', ''));
    }

    public function getAccessToken(): string
    {
        return Cache::remember('satusehat_token', 3500, function () {
            $response = Http::asForm()->post("{$this->authUrl}/accesstoken", [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type'    => 'client_credentials',
            ]);
            return $response->json('access_token');
        });
    }

    public function getFaskesByKota(string $kotaId, int $page = 1): array
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/masterdata/v1/Organization", [
                'active'  => 'true',
                '_count'  => 100,
                '_page'   => $page,
                'address-city' => $kotaId,
            ]);
        return $response->json('entry', []);
    }

    public function getImmunizationData(string $patientId): array
    {
        $token = $this->getAccessToken();
        $response = Http::withToken($token)
            ->get("{$this->baseUrl}/fhir/r4/Immunization", [
                'patient' => $patientId,
                '_count'  => 50,
            ]);
        return $response->json('entry', []);
    }
}
