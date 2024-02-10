<?php

namespace Kiwilan\Notifier\Slack;

use Kiwilan\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierShared;

class SlackMessage extends SlackContainer
{
    protected function __construct(
        protected ?string $text = null,
    ) {
    }

    public static function create(NotifierSlack $slack, string $message): self
    {
        $message = NotifierShared::truncate($message);

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
