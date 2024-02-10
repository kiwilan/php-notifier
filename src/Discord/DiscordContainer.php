<?php

namespace Kiwilan\Notifier\Discord;

use Kiwilan\Notifier\NotifierDiscord;
use Kiwilan\Notifier\Utils\NotifierHttpClient;

abstract class DiscordContainer
{
    protected function __construct(
        protected NotifierDiscord $discord,
        protected ?NotifierHttpClient $request = null,
        protected bool $isSuccess = false,
    ) {
    }

    abstract public static function create(NotifierDiscord $discord, string $description): self;

    abstract public function toArray(): array;

    public function getDiscord(): NotifierDiscord
    {
        return $this->discord;
    }

    public function getRequest(): ?NotifierHttpClient
    {
        return $this->request;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function send(bool $mock = false): static
    {
        $this->request = NotifierHttpClient::make($this->discord->getWebhook())
            ->client($this->discord->getClient())
            ->body($this->toArray())
            ->send($mock);

        $this->isSuccess = $this->request->getStatusCode() === 204 || $this->request->getStatusCode() === 200;

        if ($this->isSuccess) {
            // Log::error("Notifier: discord notification failed with HTTP {$this->request->getStatusCode()}", [
            //     $this->request->toArray(),
            // ]);
        }

        return $this;
    }
}
