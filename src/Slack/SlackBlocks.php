<?php

namespace Kiwilan\Notifier\Slack;

use Kiwilan\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierShared;

class SlackBlocks extends SlackContainer
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
            'blocks' => [
                [
                    'type' => 'section',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'Danny Torrence left the following review for your property:',
                    ],
                    'fields' => [
                        [
                            'type' => 'mrkdwn',
                            'text' => '*Markdown!*',
                        ],
                        [
                            'type' => 'plain_text',
                            'text' => 'Text!',
                        ],
                    ],
                ],
                [
                    'type' => 'divider',
                ],
                [
                    'type' => 'actions',
                    'elements' => [
                        [
                            'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Example Button',
                            ],
                            'action_id' => 'button_example-button',
                        ],
                        [
                            'type' => 'button',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => 'Scary Button',
                            ],
                            'action_id' => 'button_scary-button',
                            'style' => 'danger',
                        ],
                    ],
                ],
                [
                    'type' => 'header',
                    'text' => [
                        'type' => 'plain_text',
                        'text' => 'Budget Performance',
                    ],
                ],
                [
                    'type' => 'image',
                    'title' => [
                        'type' => 'plain_text',
                        'text' => 'Please enjoy this photo of a kitten',
                    ],
                    'block_id' => 'image4',
                    'image_url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                    'alt_text' => 'notifier banner',
                ],
                [
                    'type' => 'section',
                    'block_id' => 'section567',
                    'text' => [
                        'type' => 'mrkdwn',
                        'text' => 'Markdown *can* be so fun!',
                    ],
                    'accessory' => [
                        'type' => 'image',
                        'image_url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                        'alt_text' => 'notifier banner',
                    ],
                ],
            ],
        ];
    }
}
