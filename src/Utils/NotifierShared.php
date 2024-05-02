<?php

namespace Kiwilan\Notifier\Utils;

class NotifierShared
{
    public static function truncate(?string $string, int $length = 2000): ?string
    {
        if (! $string) {
            return null;
        }

        if (strlen($string) >= $length) {
            $string = substr($string, 0, $length - 20).'...';
        }

        return $string;
    }

    /**
     * @param  string|string[]|null  $message
     */
    public static function arrayToString(mixed $message): ?string
    {
        if (! $message) {
            return null;
        }

        if (is_string($message)) {
            return $message;
        }

        return implode(PHP_EOL, $message);
    }

    public static function checkIfStringIsUrl(?string $string): void
    {
        if (! $string) {
            NotifierShared::logError('Webhook URL is empty.');

            return;
        }

        if (! filter_var($string, FILTER_VALIDATE_URL)) {
            NotifierShared::logError("Webhook `{$string}` is not a valid URL.");
        }
    }

    public static function checkIfUrlContains(?string $url, string $string): void
    {
        if (! $url) {
            NotifierShared::logError('Webhook URL is empty.');

            return;
        }

        if (! str_contains($url, $string)) {
            throw new \Exception("Webhook `{$url}` does not contain `{$string}`.");
        }
    }

    public static function getShortcutColor(string $color): string
    {
        return match ($color) {
            'success' => '22c55e',
            'warning' => 'eab308',
            'error' => 'ef4444',
            default => '22c55e',
        };
    }

    public static function logError(string $reason, array $data = []): void
    {
        error_log($reason.PHP_EOL.json_encode($data, JSON_PRETTY_PRINT));
    }
}
