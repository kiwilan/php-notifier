<?php

namespace Kiwilan\Notifier;

use Kiwilan\Notifier\Slack\SlackAttachment;
use Kiwilan\Notifier\Slack\SlackBlocks;
use Kiwilan\Notifier\Slack\SlackMessage;
use Kiwilan\Notifier\Utils\NotifierShared;

/**
 * @see https://api.slack.com/messaging/webhooks#advanced_message_formatting
 * @see https://api.slack.com/block-kit
 */
class NotifierSlack extends Notifier
{
    protected function __construct(
        protected string $webhook,
        protected string $client = 'stream',
    ) {
    }

    public static function make(?string $webhook, string $client = 'stream'): self
    {
        return new self($webhook, $client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function message(array|string $message): SlackMessage
    {
        $message = NotifierShared::arrayToString($message);

        return SlackMessage::create($this, $message);
    }

    /**
     * @param  string[]|string  $message
     */
    public function attachment(array|string $message): SlackAttachment
    {
        $message = NotifierShared::arrayToString($message);

        return SlackAttachment::create($this, $message);
    }

    /**
     * @param  string[]|string  $message
     */
    public function blocks(array|string $message): SlackBlocks
    {
        $message = NotifierShared::arrayToString($message);

        return SlackBlocks::create($this, $message);
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
