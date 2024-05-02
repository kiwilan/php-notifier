<?php

namespace Kiwilan\Notifier;

use Closure;
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
        protected ?string $webhook,
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

    public function getWebhook(): ?string
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
