<?php

namespace Kiwilan\Notifier;

use Kiwilan\Notifier\Notifier\NotifierDiscord;
use Kiwilan\Notifier\Notifier\NotifierMail;
use Kiwilan\Notifier\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierHelpers;
use Kiwilan\Notifier\Utils\NotifierRequest;

/**
 * Send notifications to email, Slack or Discord.
 */
class Notifier
{
    public function __construct(
        protected string $type = 'unknown',
        protected array $requestData = [],
        protected ?NotifierRequest $request = null,
        protected string $client = 'stream',
    ) {
    }

    /**
     * Send notification to email with `symfony/mailer`.
     */
    public function mail(): NotifierMail
    {
        $self = new self();
        $self->type = 'mail';

        return NotifierMail::make();
    }

    /**
     * Send notification to Slack channel via webhook.
     *
     * @param  string  $webhook  Slack webhook URL, like `https://hooks.slack.com/services/X/Y/Z`
     *
     * @see https://api.slack.com/messaging/webhooks
     */
    public function slack(string $webhook): NotifierSlack
    {
        $self = new self();
        $self->type = 'slack';

        NotifierHelpers::checkIfStringIsUrl($webhook);
        NotifierHelpers::checkIfUrlContains($webhook, 'slack.com');

        return NotifierSlack::make($webhook, $this->client);
    }

    /**
     * Send notification to Discord channel via webhook.
     *
     * @param  string  $webhook  Discord webhook URL, like `https://discord.com/api/webhooks/X/Y`
     *
     * @see https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks
     */
    public function discord(string $webhook): NotifierDiscord
    {
        $self = new self();
        $self->type = 'discord';

        NotifierHelpers::checkIfStringIsUrl($webhook);
        NotifierHelpers::checkIfUrlContains($webhook, 'discord.com');

        return NotifierDiscord::make($webhook, $this->client);
    }

    /**
     * Set the HTTP client to use.
     *
     * @param  string  $client  `stream`, `curl` or `guzzle`. Default is `stream`.
     */
    public function client(string $client): self
    {
        $this->client = $client;

        return $this;
    }
}
