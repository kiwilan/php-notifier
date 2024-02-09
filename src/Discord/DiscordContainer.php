<?php

namespace Kiwilan\Notifier\Discord;

use Illuminate\Support\Facades\Log;
use Kiwilan\Notifier\NotifierDiscord;
use Kiwilan\Notifier\Utils\NotifierRequest;

abstract class DiscordContainer
{
    protected function __construct(
        protected NotifierDiscord $discord,
        protected ?NotifierRequest $request = null,
        protected bool $isSuccess = false,
    ) {
    }

    abstract public static function create(NotifierDiscord $discord, string $description): self;

    abstract public function toArray(): array;

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function send(): static
    {
        $this->request = NotifierRequest::make($this->discord->getWebhook())
            ->requestData($this->toArray())
            ->send();

        // if ($this->client === 'stream') {
        //     $this->request->useStream();
        // }

        // if ($this->client === 'curl') {
        //     $this->request->useCurl();
        // }

        // if ($this->client === 'guzzle') {
        //     $this->request->useGuzzle();
        // }

        $this->isSuccess = $this->request->getStatusCode() === 204;

        if ($this->isSuccess) {
            // Log::error("Notifier: discord notification failed with HTTP {$this->request->getStatusCode()}", [
            //     $this->request->toArray(),
            // ]);
        }

        return $this;
    }
}
