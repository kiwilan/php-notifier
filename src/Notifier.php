<?php

namespace Kiwilan\Notifier;

use Kiwilan\Notifier\Utils\NotifierHttpClient;
use Kiwilan\Notifier\Utils\NotifierShared;

/**
 * Send notifications to email, Slack or Discord.
 */
class Notifier
{
    public function __construct(
        protected string $type = 'unknown',
        protected array $requestData = [],
        protected ?NotifierHttpClient $request = null,
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
    public function slack(string $webhook, string $client = 'stream'): NotifierSlack
    {
        $self = new self();
        $self->type = 'slack';

        NotifierShared::checkIfStringIsUrl($webhook);
        NotifierShared::checkIfUrlContains($webhook, 'slack.com');
        $this->setClientIfNotStream($client);

        return NotifierSlack::make($webhook, $this->client);
    }

    /**
     * Send notification to Discord channel via webhook.
     *
     * @param  string  $webhook  Discord webhook URL, like `https://discord.com/api/webhooks/X/Y`
     *
     * @see https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks
     */
    public function discord(string $webhook, string $client = 'stream'): NotifierDiscord
    {
        $self = new self();
        $self->type = 'discord';

        NotifierShared::checkIfStringIsUrl($webhook);
        NotifierShared::checkIfUrlContains($webhook, 'discord.com');
        $this->setClientIfNotStream($client);

        return NotifierDiscord::make($webhook, $this->client);
    }

    /**
     * Send notification to any URL, you can specify HTTP method, headers and body.
     *
     * @param  string  $url  Any URL, like `https://example.com`
     */
    public function http(string $url, string $client = 'stream'): NotifierHttp
    {
        $self = new self();
        $self->type = 'request';

        NotifierShared::checkIfStringIsUrl($url);
        $this->setClientIfNotStream($client);

        return NotifierHttp::make($url, $this->client);
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

    private function setClientIfNotStream(string $client): void
    {
        if ($client !== 'stream') {
            $this->client($client);
        }
    }
}
