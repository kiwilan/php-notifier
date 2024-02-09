<?php

namespace Kiwilan\Notifier\Slack;

use Illuminate\Support\Facades\Log;
use Kiwilan\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierRequest;

abstract class SlackContainer
{
    protected function __construct(
        protected NotifierSlack $slack,
        protected ?NotifierRequest $request = null,
        protected bool $isSuccess = false,
    ) {
    }

    abstract public static function create(NotifierSlack $slack, string $message): self;

    abstract public function toArray(): array;

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function send(): static
    {
        $this->request = NotifierRequest::make($this->slack->getWebhook())
            ->requestData($this->toArray())
            ->send();

        $this->isSuccess = $this->request->getStatusCode() === 200;

        if ($this->isSuccess) {
            // Log::error("Notifier: slack notification failed with HTTP {$this->request->getStatusCode()}", [
            //     $this->request->toArray(),
            // ]);
            // dump($this);
        }

        return $this;
    }
}
