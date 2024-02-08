<?php

namespace Kiwilan\Notifier\Notifier;

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\Notifier\Slack\NotifierSlackAttachment;
use Kiwilan\Notifier\Notifier\Slack\NotifierSlackBlocks;
use Kiwilan\Notifier\Notifier\Slack\NotifierSlackMessage;
use Kiwilan\Notifier\Utils\NotifierHelpers;

/**
 * @see https://api.slack.com/messaging/webhooks#advanced_message_formatting
 * @see https://api.slack.com/block-kit
 */
class NotifierSlack extends Notifier
{
    protected function __construct(
        protected string $webhook,
        protected string $client = 'stream',
        protected ?string $message = null,
    ) {
    }

    public static function make(string $webhook, string $client): self
    {
        return new self($webhook, $client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function message(array|string $message): NotifierSlackMessage
    {
        $message = NotifierHelpers::arrayToString($message);

        return NotifierSlackMessage::create($this->webhook, $message, $this->client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function attachment(array|string $message): NotifierSlackAttachment
    {
        $message = NotifierHelpers::arrayToString($message);

        return NotifierSlackAttachment::create($this->webhook, $message, $this->client);
    }

    /**
     * @param  string[]|string  $message
     */
    public function blocks(array|string $message): NotifierSlackBlocks
    {
        $message = NotifierHelpers::arrayToString($message);

        return NotifierSlackBlocks::create($this->webhook, $message, $this->client);
    }
}
