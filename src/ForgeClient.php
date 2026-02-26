<?php

declare(strict_types=1);

namespace Centrix\Forge;

/** Client for a Forge rendering server. */
class ForgeClient
{
    private string $baseUrl;
    private int $timeout;

    public function __construct(string $baseUrl, int $timeout = 120)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
    }

    /** Start a render request from an HTML string. */
    public function renderHtml(string $html): RenderRequestBuilder
    {
        return new RenderRequestBuilder($this, html: $html);
    }

    /** Start a render request from a URL. */
    public function renderUrl(string $url): RenderRequestBuilder
    {
        return new RenderRequestBuilder($this, url: $url);
    }

    /** Check if the server is healthy. */
    public function health(): bool
    {
        try {
            $ch = curl_init("{$this->baseUrl}/health");
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $this->timeout,
            ]);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $code === 200;
        } catch (\Throwable) {
            return false;
        }
    }

    /** @internal Send a render payload. Called by RenderRequestBuilder. */
    public function sendRender(array $payload): string
    {
        $json = json_encode($payload, JSON_THROW_ON_ERROR);

        $ch = curl_init("{$this->baseUrl}/render");
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $json,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
        ]);

        $body = curl_exec($ch);

        if ($body === false) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new ForgeConnectionException(new \RuntimeException($error));
        }

        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($code !== 200) {
            $decoded = json_decode($body, true);
            $message = $decoded['error'] ?? "HTTP {$code}";
            throw new ForgeServerException($code, $message);
        }

        return $body;
    }
}
