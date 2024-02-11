<?php

namespace Kiwilan\Notifier;

use Closure;
use Kiwilan\Notifier\Utils\NotifierHttpClient;

class NotifierHttp extends Notifier
{
    protected function __construct(
        protected string $url,
        protected string $client = 'stream',
        protected string $method = 'GET',
        protected array $headers = [],
        protected array $body = [],
        protected ?NotifierHttpClient $request = null,
        protected ?Closure $logError = null,
        protected ?Closure $logSent = null,
    ) {
    }

    public static function make(string $url, string $client = 'stream'): self
    {
        return new self($url, $client);
    }

    /**
     * @param  string  $method  The request method: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`. Default is `GET`.
     */
    public function method(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function body(array $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param  string[]  $headers  The request headers
     */
    public function header(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function send(bool $mock = false): self
    {
        $this->request = NotifierHttpClient::make($this->url)
            ->client($this->client)
            ->method($this->method)
            ->headers($this->headers)
            ->body($this->body)
            ->send($mock);

        $statusCode = $this->request->getStatusCode();
        $okList = [200, 201, 202, 204];
        $isSuccess = in_array($statusCode, $okList);

        if (! $isSuccess) {
            if ($this->logError) {
                $this->getLogError('HTTP '.$statusCode, $this->request->toArray());
            } else {
                error_log('HTTP '.$statusCode.': '.json_encode($this->request->toArray()));
            }

            return $this;
        }

        if ($this->logSent) {
            $this->getLogSent($this->request->toArray());
        }

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRequest(): ?NotifierHttpClient
    {
        return $this->request;
    }

    public function logError(Closure $closure): self
    {
        $this->logError = $closure;

        return $this;
    }

    public function getLogError(string $reason, array $data = []): void
    {
        if ($this->logError) {
            ($this->logError)($reason, $data);
        }
    }

    public function logSent(Closure $closure): self
    {
        $this->logSent = $closure;

        return $this;
    }

    public function getLogSent(array $data = []): void
    {
        if ($this->logSent) {
            ($this->logSent)($data);
        }
    }
}
