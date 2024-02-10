<?php

namespace Kiwilan\Notifier;

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
    ) {
    }

    public static function make(string $webhook, string $client): self
    {
        return new self($webhook, $client);
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
}
