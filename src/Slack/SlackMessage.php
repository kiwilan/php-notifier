<?php

namespace Kiwilan\Notifier\Slack;

use Kiwilan\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierHelpers;

class SlackMessage extends SlackContainer
{
    protected function __construct(
        protected ?string $text = null,
    ) {
    }

    public static function create(NotifierSlack $slack, string $message): self
    {
        $message = NotifierHelpers::truncate($message);

        $self = new self($message);
        $self->slack = $slack;

        return $self;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }
}
