<?php

namespace Kiwilan\Notifier\Notifier;

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\Notifier\Discord\DiscordMessage;
use Kiwilan\Notifier\Notifier\Discord\DiscordRich;
use Kiwilan\Notifier\Utils\NotifierHelpers;

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
    ) {
    }

    public static function make(string $webhook, string $client): self
    {
        return new self($webhook, $client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function message(array|string $message): DiscordMessage
    {
        $message = NotifierHelpers::arrayToString($message);

        return DiscordMessage::create($this, $message);
    }

    /**
     * @param  string[]|string  $message
     */
    public function rich(array|string $message): DiscordRich
    {
        $message = NotifierHelpers::arrayToString($message);

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
}
