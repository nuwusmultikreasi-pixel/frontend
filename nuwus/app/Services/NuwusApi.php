<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Penghubung Laravel (frontend) ke REST API Django (backend).
 */
class NuwusApi
{
    /** @param  string|null  $token  null = pakai token dari session login; '' = tanpa token (publik) */
    public function __construct(private ?string $token = null) {}

    protected function client(): PendingRequest
    {
        $req = Http::baseUrl(config('nuwus.api_url'))
            ->timeout(config('nuwus.api_timeout'))
            ->acceptJson();

        $token = $this->token ?? session('api_token');

        return $token ? $req->withHeaders(['Authorization' => 'Token '.$token]) : $req;
    }

    /** GET yang aman: bila backend mati, kembalikan nilai default agar halaman tetap tampil. */
    public function safeGet(string $uri, mixed $default = []): mixed
    {
        try {
            $res = $this->client()->get($uri);

            return $res->successful() ? $res->json() : $default;
        } catch (ConnectionException $e) {
            Log::warning('Backend Django tidak dapat dihubungi: '.$e->getMessage());

            return $default;
        }
    }

    public function get(string $uri): Response
    {
        return $this->client()->get($uri);
    }

    public function delete(string $uri): Response
    {
        return $this->client()->delete($uri);
    }

    public function post(string $uri, array $data = []): Response
    {
        return $this->client()->post($uri, $data);
    }

    /**
     * Kirim form multipart (bisa berisi file) ke Django.
     *
     * @param  array<string, UploadedFile|null>  $files
     */
    public function sendForm(string $method, string $uri, array $fields, array $files = []): Response
    {
        $req = $this->client()->asMultipart();

        foreach ($files as $name => $file) {
            if ($file instanceof UploadedFile) {
                $req = $req->attach($name, fopen($file->getRealPath(), 'r'), $file->getClientOriginalName());
            }
        }

        $fields = array_map(fn ($v) => is_bool($v) ? ($v ? 'true' : 'false') : (string) ($v ?? ''), $fields);

        return $req->send(strtoupper($method), $uri, ['multipart' => $this->toMultipart($fields)]);
    }

    private function toMultipart(array $fields): array
    {
        $parts = [];
        foreach ($fields as $name => $value) {
            $parts[] = ['name' => $name, 'contents' => $value];
        }

        return $parts;
    }

    /** Ubah error validasi DRF ({field: [pesan]}) menjadi format errors Laravel. */
    public static function errors(Response $res): array
    {
        $json = $res->json();
        if (! is_array($json)) {
            return ['api' => 'Terjadi kesalahan pada server backend (HTTP '.$res->status().').'];
        }
        $out = [];
        foreach ($json as $field => $messages) {
            $key = $field === 'non_field_errors' || $field === 'detail' ? 'api' : $field;
            $out[$key] = is_array($messages) ? implode(' ', $messages) : (string) $messages;
        }

        return $out;
    }
}
