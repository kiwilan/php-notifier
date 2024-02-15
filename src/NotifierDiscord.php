<?php

namespace Kiwilan\Notifier;

use Closure;
use Kiwilan\Notifier\Discord\DiscordMessage;
use Kiwilan\Notifier\Discord\DiscordRich;
use Kiwilan\Notifier\Utils\NotifierShared;

/**
 * @see https://gist.github.com/Birdie0/78ee79402a4301b1faf412ab5f1cdcf9
 * @see https://birdie0.github.io/discord-webhooks-guide/
 * @see https://github.com/spatie/laravel-backup/blob/main/src/Notifications/Channels/Discord/DiscordMessage.php
 */
class NotifierDiscord extends Notifier
{
    protected function __construct(
        protected string $webhook,
        protected string $client = 'stream',
        protected ?Closure $logError = null,
        protected ?Closure $logSent = null,
    ) {
    }

    public static function make(?string $webhook, string $client = 'stream'): self
    {
        return new self($webhook, $client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function message(array|string $message): DiscordMessage
    {
        $message = NotifierShared::arrayToString($message);

        return DiscordMessage::create($this, $message);
    }

    /**
     * @param  string[]|string  $message
     */
    public function rich(array|string $message): DiscordRich
    {
        $message = NotifierShared::arrayToString($message);

        return DiscordRich::create($this, $message);
    }

    public function getWebhook(): string
    {
        return $this->webhook;
    }

    public function getClient(): string
    {
        return $this->client;
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
        } else {
            NotifierShared::logError($reason, $data);
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
