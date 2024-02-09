<?php

namespace Kiwilan\Notifier\Discord;

use DateTime;
use Kiwilan\Notifier\NotifierDiscord;
use Kiwilan\Notifier\Utils\NotifierHelpers;

class DiscordRich extends DiscordContainer
{
    protected function __construct(
        protected ?string $description = null,
        protected ?string $username = null,
        protected ?string $avatarUrl = null,
        protected ?string $authorName = null,
        protected ?string $authorUrl = null,
        protected ?string $authorIconUrl = null,
        protected ?string $url = null,
        protected ?string $title = null,
        protected ?string $timestamp = null,
        protected ?string $color = null,
        protected ?array $fields = null,
        protected ?string $thumbnail = null,
        protected ?string $image = null,
        protected ?string $footerText = null,
        protected ?string $footerIconUrl = null,
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

    public function author(string $name, ?string $url = null, ?string $iconUrl = null): self
    {
        $this->authorName = $name;

        if ($url) {
            $this->authorUrl = $url;
        }

        if ($iconUrl) {
            $this->authorIconUrl = $iconUrl;
        }

        return $this;
    }

    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function title($title): self
    {
        $this->title = $title;

        return $this;
    }

    public function timestamp(?DateTime $timestamp = null): self
    {
        if (! $timestamp) {
            $timestamp = new DateTime();
        }

        $this->timestamp = $timestamp->format(DateTime::ATOM);

        return $this;
    }

    public function footer(string $footer, ?string $iconUrl = null): self
    {
        $this->footerText = $footer;

        if ($iconUrl) {
            $this->footerIconUrl = $iconUrl;
        }

        return $this;
    }

    /**
     * Set a color to rich embed, you can use shortcut methods like `success`, `warning`, `error`
     *
     * @param  string  $color  Add hex color code (with or without `#` prefix)
     */
    public function color(string $color): self
    {
        if (str_contains($color, '#')) {
            $color = str_replace('#', '', $color);
        }

        $this->color = $color;

        return $this;
    }

    /**
     * Set a green color to rich embed
     */
    public function colorSuccess(): self
    {
        $this->color = NotifierHelpers::getShortcutColor('success');

        return $this;
    }

    /**
     * Set a yellow color to rich embed
     */
    public function colorWarning(): self
    {
        $this->color = NotifierHelpers::getShortcutColor('warning');

        return $this;
    }

    /**
     * Set a red color to rich embed
     */
    public function colorError(): self
    {
        $this->color = NotifierHelpers::getShortcutColor('error');

        return $this;
    }

    /**
     * Add fields to rich embed
     *
     * @param  array{name: string, value: string|int}  $fields  Array of fields, each field should have `name` and `value`
     * @param  bool  $inline  Set to `true` if you want to display fields inline
     */
    public function fields(array $fields, bool $inline = false): self
    {
        foreach ($fields as $field) {
            $this->fields[] = [
                'name' => $field['name'] ?? 'Field',
                'value' => $field['value'] ?? 'Value',
                'inline' => $inline,
            ];
        }

        return $this;
    }

    public function thumbnail(string $url): self
    {
        $this->thumbnail = $url;

        return $this;
    }

    public function image(string $url): self
    {
        $this->image = $url;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'avatar_url' => $this->avatarUrl,
            'embeds' => [
                [
                    'author' => [
                        'name' => $this->authorName,
                        'url' => $this->authorUrl,
                        'icon_url' => $this->authorIconUrl,
                    ],
                    'title' => $this->title,
                    'url' => $this->url,
                    'type' => 'rich',
                    'description' => $this->description,
                    'fields' => $this->fields,
                    'color' => $this->color ? hexdec($this->color) : null,
                    'thumbnail' => [
                        'url' => $this->thumbnail,
                    ],
                    'image' => [
                        'url' => $this->image,
                    ],
                    'footer' => [
                        'text' => $this->footerText,
                        'icon_url' => $this->footerIconUrl,
                    ],
                    'timestamp' => $this->timestamp,
                ],
            ],
        ];
    }
}
