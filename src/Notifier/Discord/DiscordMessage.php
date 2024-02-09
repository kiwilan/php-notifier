<?php

namespace Kiwilan\Notifier\Notifier\Discord;

use Kiwilan\Notifier\Notifier\NotifierDiscord;
use Kiwilan\Notifier\Utils\NotifierHelpers;

class DiscordMessage extends DiscordContainer
{
    protected function __construct(
        protected ?string $message = null,
        protected ?string $username = null,
        protected ?string $avatarUrl = null,
    ) {
    }

    public static function create(NotifierDiscord $discord, string $message): self
    {
        $message = NotifierHelpers::truncate($message);

        $self = new self($message);
        $self->discord = $discord;

        return $self;
    }

    public function user(string $username, ?string $avatarUrl = null): self
    {
        $this->username = $username;

        if ($avatarUrl) {
            $this->avatarUrl = $avatarUrl;
        }

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->username) {
            $data['username'] = $this->username;
        }

        if ($this->avatarUrl) {
            $data['avatar_url'] = $this->avatarUrl;
        }

        $data['content'] = $this->message ?? 'Empty message.';

        return $data;
    }
}
