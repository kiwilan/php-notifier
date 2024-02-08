<?php

namespace Kiwilan\Notifier\Notifier;

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\Notifier\Discord\NotifierDiscordMessage;
use Kiwilan\Notifier\Notifier\Discord\NotifierDiscordRich;
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
    public function message(array|string $message): NotifierDiscordMessage
    {
        $message = NotifierHelpers::arrayToString($message);

        return NotifierDiscordMessage::create($this->webhook, $message, $this->client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function rich(array|string $message): NotifierDiscordRich
    {
        $message = NotifierHelpers::arrayToString($message);

        return NotifierDiscordRich::create($this->webhook, $message, $this->client);
    }
}
