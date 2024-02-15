<?php

namespace Kiwilan\Notifier\Slack;

use DateTime;
use Kiwilan\Notifier\NotifierSlack;
use Kiwilan\Notifier\Utils\NotifierShared;

class SlackAttachment extends SlackContainer
{
    /**
     * @param  array{name: string, value: string, short: bool}  $fields  Array of fields, each field should have `name` and `value`
     */
    protected function __construct(
        protected ?string $messageText = null,
        protected ?string $color = null,
        protected ?string $pretext = null,
        protected ?string $authorName = null,
        protected ?string $authorLink = null,
        protected ?string $authorIcon = null,
        protected ?string $title = null,
        protected ?string $titleLink = null,
        protected ?string $text = null,
        protected ?array $fields = null,
        protected ?string $imageUrl = null,
        protected ?string $footer = null,
        protected ?string $footerIcon = null,
        protected ?int $ts = null,
    ) {
    }

    public static function create(NotifierSlack $slack, string $message): self
    {
        $message = NotifierShared::truncate($message);

        $self = new self($message);
        $self->slack = $slack;

        return $self;
    }

    /**
     * Set a green color to rich embed
     */
    public function colorSuccess(): self
    {
        $this->color = '#'.NotifierShared::getShortcutColor('success');

        return $this;
    }

    /**
     * Set a yellow color to rich embed
     */
    public function colorWarning(): self
    {
        $this->color = '#'.NotifierShared::getShortcutColor('warning');

        return $this;
    }

    /**
     * Set a red color to rich embed
     */
    public function colorError(): self
    {
        $this->color = '#'.NotifierShared::getShortcutColor('error');

        return $this;
    }

    /**
     * Set a color to rich embed, you can use shortcut methods like `success`, `warning`, `error`
     *
     * @param  string  $color  Add hex color code (with or without `#` prefix)
     */
    public function color(string $color): self
    {
        if (! str_contains($color, '#')) {
            $color = "#{$color}";
        }

        $this->color = $color;

        return $this;
    }

    public function pretext(string $pretext): self
    {
        $this->pretext = $pretext;

        return $this;
    }

    public function author(string $name, ?string $link = null, ?string $icon = null): self
    {
        $this->authorName = $name;

        if ($link) {
            $this->authorLink = $link;
        }

        if ($icon) {
            $this->authorIcon = $icon;
        }

        return $this;
    }

    public function title(string $title, ?string $link = null): self
    {
        $this->title = $title;

        if ($link) {
            $this->titleLink = $link;
        }

        return $this;
    }

    public function text(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Add fields to the attachment
     *
     * @param  array{name: string, value: mixed, short: bool}  $fields  Array of fields, each field should have `name` and `value`
     */
    public function fields(array $fields): self
    {
        // $this->fields = $fields;

        foreach ($fields as $field) {
            $value = $field['value'] ?? 'Value';
            if (! is_string($value)) {
                $value = json_encode($value);
            }

            $this->fields[] = [
                'name' => $field['name'] ?? 'Field',
                'value' => NotifierShared::truncate($value),
                'inline' => $field['short'] ?? false,
            ];
        }

        return $this;
    }

    public function imageUrl(string $url): self
    {
        $this->imageUrl = $url;

        return $this;
    }

    public function footer(string $text, ?string $icon = null): self
    {
        $this->footer = $text;

        if ($icon) {
            $this->footerIcon = $icon;
        }

        return $this;
    }

    public function timestamp(?DateTime $timestamp = null): self
    {
        if (! $timestamp) {
            $timestamp = new DateTime();
        }

        $this->ts = $timestamp->getTimestamp();

        return $this;
    }

    public function toArray(): array
    {
        return [
            'text' => $this->messageText,
            'attachments' => [
                [
                    'mrkdwn_in' => ['text'],
                    'color' => $this->color,
                    'pretext' => $this->pretext,
                    'author_name' => $this->authorName,
                    'author_link' => $this->authorLink,
                    'author_icon' => $this->authorIcon,
                    'title' => $this->title,
                    'title_link' => $this->titleLink,
                    'text' => $this->text,
                    'fields' => $this->fields,
                    'image_url' => $this->imageUrl,
                    'footer' => $this->footer,
                    'footer_icon' => $this->footerIcon,
                    'ts' => $this->ts,
                ],
            ],
        ];
    }
}
