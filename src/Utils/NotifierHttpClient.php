<?php

namespace Kiwilan\Notifier\Utils;

class NotifierHttpClient
{
    /**
     * Create a new NotifierHttpClient instance.
     *
     * @param  string  $url  The URL to send the request to
     * @param  string  $mode  The request mode: `stream`, `curl`, or `guzzle`
     * @param  array  $request_headers  The request headers
     * @param  array  $request_body  The request body
     * @param  string  $request_method  The request method: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`
     * @param  array|null  $response_headers  The response headers
     * @param  array|null  $response_body  The response body
     * @param  int|null  $status_code  The status code
     * @param  bool  $json  Whether to send the request as JSON
     */
    protected function __construct(
        protected string $url,
        protected string $mode = 'stream',
        protected array $request_headers = [],
        protected array $request_body = [],
        protected string $request_method = 'POST',
        protected ?array $response_headers = [],
        protected ?array $response_body = [],
        protected ?int $status_code = null,
        protected bool $json = true,
        protected bool $mock = false,
    ) {
    }

    /**
     * Create a new NotifierHttpClient instance.
     */
    public static function make(?string $url)
    {
        if (! $url) {
            throw new \Exception('URL is required.');
        }

        $url = trim($url);

        return new self($url);
    }

    /**
     * Use stream to send HTTP request.
     */
    public function useStream(): self
    {
        $this->mode = 'stream';

        return $this;
    }

    /**
     * Use cURL to send HTTP request.
     */
    public function useCurl(): self
    {
        $this->mode = 'curl';

        return $this;
    }

    /**
     * Use Guzzle to send HTTP request.
     */
    public function useGuzzle(): self
    {
        $this->mode = 'guzzle';

        return $this;
    }

    /**
     * Set the request mode: `stream`, `curl`, or `guzzle`.
     */
    public function client(string $client): self
    {
        $this->mode = match ($client) {
            'stream' => 'stream',
            'curl' => 'curl',
            'guzzle' => 'guzzle',
            default => 'stream',
        };

        return $this;
    }

    /**
     * Set the request data.
     */
    public function body(array $body): self
    {
        $this->request_body = $body;

        return $this;
    }

    /**
     * Set the request method: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`.
     */
    public function method(string $method): self
    {
        $this->request_method = strtoupper($method);

        return $this;
    }

    public function headers(array $headers): self
    {
        $this->request_headers = $headers;

        return $this;
    }

    public function asJson(): self
    {
        $this->json = true;

        return $this;
    }

    public function asForm(): self
    {
        $this->json = false;

        return $this;
    }

    /**
     * Send HTTP request.
     */
    public function send(bool $mock = false): self
    {
        $this->mock = $mock;

        try {
            if ($this->mode === 'stream') {
                $this->stream($mock);
            } elseif ($this->mode === 'curl') {
                $this->curl($mock);
            } elseif ($this->mode === 'guzzle') {
                $this->guzzle($mock);
            } else {
                throw new \Exception('Invalid request mode.');
            }
        } catch (\Throwable $th) {
            $this->status_code = 500;
            $this->response_body = [
                'error' => $th->getMessage(),
            ];
        }

        return $this;
    }

    private function mockedResponse(): void
    {
        if ($this->mock) {
            $this->response_headers = [];
            $this->status_code = 200;
            $this->response_body = [
                'message' => 'Mocked response',
            ];
        }
    }

    /**
     * Send HTTP request using stream.
     */
    private function stream(): void
    {
        $headers = $this->request_headers;
        if ($this->json) {
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        $context = stream_context_create([
            'http' => [
                'method' => $this->request_method,
                'header' => implode("\r\n", $headers),
                'content' => $this->json ? json_encode($this->request_body) : http_build_query($this->request_body),
            ],
        ]);

        $this->mockedResponse();
        if ($this->mock) {
            return;
        }

        $response = file_get_contents($this->url, false, $context);
        $headers = $http_response_header;

        $this->response_headers = $headers;
        $this->status_code = (int) explode(' ', $headers[0])[1];
        $this->response_body = json_decode($response, true);
    }

    /**
     * Send HTTP request using cURL.
     */
    private function curl(): void
    {
        $ch = curl_init($this->url);
        $headers = $this->request_headers;
        if ($this->json) {
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($this->request_method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        } elseif ($this->request_method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        } elseif ($this->request_method === 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        } elseif ($this->request_method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->request_body));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $this->mockedResponse();
        if ($this->mock) {
            return;
        }

        $response = curl_exec($ch);

        $this->response_headers = curl_getinfo($ch);
        $this->status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->response_body = json_decode($response, true);

        curl_close($ch);
    }

    /**
     * Send HTTP request using Guzzle.
     */
    private function guzzle(): void
    {
        if (! \Composer\InstalledVersions::isInstalled('guzzlehttp/guzzle')) {
            throw new \Exception('Package `guzzlehttp/guzzle` not installed, see: https://github.com/guzzle/guzzle');
        }

        $client = new \GuzzleHttp\Client();
        $body = $this->json ? 'json' : 'form_params';

        $this->mockedResponse();
        if ($this->mock) {
            return;
        }

        $response = $client->request($this->request_method, $this->url, [
            $body => $this->request_body,
        ]);

        $this->response_headers = $response->getHeaders();
        $this->status_code = $response->getStatusCode();
        $this->response_body = json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Get the request mode: `stream`, `curl`, or `guzzle`.
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    /**
     * Get the request URL.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Get the request method.
     */
    public function getRequestMethod(): string
    {
        return $this->request_method;
    }

    /**
     * Get the request headers.
     *
     * @return string[]
     */
    public function getRequestHeaders(): array
    {
        return $this->request_headers;
    }

    /**
     * Get the request body.
     */
    public function getRequestBody(): array
    {
        return $this->request_body;
    }

    /**
     * Get the response body.
     */
    public function getResponseBody(): array
    {
        return $this->response_body;
    }

    /**
     * Get the status code.
     */
    public function getStatusCode(): ?int
    {
        return $this->status_code;
    }

    /**
     * Get the response headers.
     *
     * @return string[]
     */
    public function getResponseHeaders(): ?array
    {
        return $this->response_headers;
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'mode' => $this->mode,
            'request_method' => $this->request_method,
            'request_headers' => $this->request_headers,
            'request_body' => $this->request_body,
            'response_headers' => $this->response_headers,
            'response_body' => $this->response_body,
            'status_code' => $this->status_code,
            'json' => $this->json,
            'mock' => $this->mock,
        ];
    }
}
