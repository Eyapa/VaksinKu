<?php
// app/Services/SatuSehatService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SatuSehatService
{
    private string $baseUrl;
    private string $authUrl;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->baseUrl       = config('satusehat.base_url');
        $this->authUrl       = config('satusehat.auth_url');
        $this->clientId      = config('satusehat.client_id');
        $this->clientSecret  = config('satusehat.client_secret');
    }

    /**
     * Ambil access token — di-cache selama ~58 menit (token valid 1 jam).
     */
    public function getAccessToken(): string
    {
        return Cache::remember('satusehat_access_token', 3480, function () {
            try {
                $response = Http::timeout(15)
                    ->asForm()
                    ->post("{$this->authUrl}/accesstoken?grant_type=client_credentials", [
                        'client_id'     => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]);

                if ($response->failed()) {
                    Log::error('SATUSEHAT auth gagal', ['status' => $response->status(), 'response' => $response->json()]);
                    throw new \RuntimeException('Gagal autentikasi ke SATUSEHAT API');
                }

                return $response->json('access_token');
            } catch (\Exception $e) {
                Log::error('SATUSEHAT auth exception', ['message' => $e->getMessage()]);
                throw new \RuntimeException('Gagal autentikasi ke SATUSEHAT API: ' . $e->getMessage());
            }
        });
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::withToken($this->getAccessToken())
                   ->timeout(20)
                   ->acceptJson();
    }

    // ── Organization (Fasilitas Kesehatan) ───────────────────

    public function searchOrganization(string $keyword, int $count = 20): array
    {
        try {
            $params = [
                'name'    => $keyword,
                '_count'  => $count,
                'active'  => 'true',
            ];

            $response = $this->http()->get("{$this->baseUrl}/fhir-r4/v1/Organization", $params);

            return $this->parseBundle($response->json());
        } catch (\Exception $e) {
            Log::error('Gagal fetch Organization', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getOrganizationByPartOf(string $parentId, int $count = 100): array
    {
        try {
            $response = $this->http()->get("{$this->baseUrl}/fhir-r4/v1/Organization", [
                'partOf'  => $parentId,
                'active'  => 'true',
                '_count'  => $count,
            ]);

            return $this->parseBundle($response->json());
        } catch (\Exception $e) {
            Log::error('Gagal fetch Organization by partOf', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getOrganizationById(string $id): ?array
    {
        try {
            $response = $this->http()
                ->get("{$this->baseUrl}/fhir-r4/v1/Organization/{$id}");

            if ($response->notFound()) return null;

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Gagal fetch Organization by ID', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    // ── Patient (Pasien) ──────────────────────────────────────

    public function getPatientByNik(string $nik): ?array
    {
        try {
            $response = $this->http()->get("{$this->baseUrl}/fhir-r4/v1/Patient", [
                'identifier' => "https://fhir.kemkes.go.id/id/nik|{$nik}",
            ]);

            $entries = $this->parseBundle($response->json());

            return $entries[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Gagal fetch Patient by NIK', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    // ── Helper ────────────────────────────────────────────────

    private function parseBundle(?array $bundle): array
    {
        if (!$bundle || ($bundle['resourceType'] ?? '') !== 'Bundle') {
            return [];
        }

        return collect($bundle['entry'] ?? [])
            ->pluck('resource')
            ->filter()
            ->values()
            ->all();
    }

    public function normalizeOrganization(array $org): array
    {
        $telecom = collect($org['telecom'] ?? []);
        $address = $org['address'][0] ?? [];
        
        $latitude = null;
        $longitude = null;
        
        // Coba ekstrak dari extension geolocation (FHIR standard)
        $extensions = $address['extension'] ?? $org['extension'] ?? [];
        foreach ($extensions as $ext) {
            if (isset($ext['extension'])) {
                foreach ($ext['extension'] as $subExt) {
                    if (($subExt['url'] ?? '') === 'latitude') {
                        $latitude = $subExt['valueDecimal'] ?? null;
                    }
                    if (($subExt['url'] ?? '') === 'longitude') {
                        $longitude = $subExt['valueDecimal'] ?? null;
                    }
                }
            }
        }
        
        // Fallback jika dikirimkan dalam node 'position' (non-standard but possible)
        if (!$latitude && isset($org['position']['latitude'])) {
            $latitude = $org['position']['latitude'];
        }
        if (!$longitude && isset($org['position']['longitude'])) {
            $longitude = $org['position']['longitude'];
        }

        return [
            'satusehat_id' => $org['id']     ?? null,
            'nama'         => $org['name']   ?? 'Tidak Diketahui',
            'jenis'        => $this->mapOrganizationType($org['type'] ?? []),
            'alamat'       => $address['text'] ?? implode(', ', $address['line'] ?? []),
            'kota'         => $address['city']     ?? null,
            'provinsi'     => $address['state']    ?? null,
            'kode_pos'     => $address['postalCode'] ?? null,
            'latitude'     => $latitude,
            'longitude'    => $longitude,
            'telepon'      => $telecom->firstWhere('system', 'phone')['value'] ?? null,
            'website'      => $telecom->firstWhere('system', 'url')['value']   ?? null,
            'layanan_vaksin' => true, // default layani vaksin untuk data sandbox
        ];
    }

    private function mapOrganizationType(array $types): string
    {
        $codes = collect($types)
            ->flatMap(fn($t) => $t['coding'] ?? [])
            ->pluck('code')
            ->map('strtolower')
            ->all();

        if (in_array('puskesmas', $codes) || str_contains(implode(',', $codes), 'puskesmas')) return 'puskesmas';
        if (in_array('rs', $codes) || str_contains(implode(',', $codes), 'rs')) return 'rs_umum';
        if (in_array('klinik', $codes) || str_contains(implode(',', $codes), 'klinik')) return 'klinik';
        if (in_array('apotek', $codes) || str_contains(implode(',', $codes), 'apotek')) return 'apotek';
        
        return 'klinik'; // fallback default
    }
}
