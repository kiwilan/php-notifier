<?php

function dotenv(): array
{
    $path = __DIR__.'/../';
    $lines = file($path.'.env');
    $dotenv = [];

    foreach ($lines as $line) {
        if (! empty($line)) {
            $data = explode('=', $line);
            $key = $data[0];
            if ($key === " \n ") {
                continue;
            }
            unset($data[0]);
            $value = implode('=', $data);

            $key = $key ? trim($key) : '';
            $value = $value ? trim($value) : '';

            if ($key === '') {
                continue;
            }

            $value = str_replace('"', '', $value);
            $value = str_replace("'", '', $value);

            $dotenv[$key] = $value;
        }
    }

    return $dotenv;
}

function getDotenv(string $key): string
{
    return dotenv()[$key] ?? '';
}

function mock(): bool
{
    $mock = dotenv()['NOTIFIER_MOCK'];
    if (! $mock) {
        return false;
    }

    if ($mock === 'true' || $mock === true) {
        return true;
    }

    return false;
}

function getLog(): string
{
    $os = PHP_OS;
    $cmd = match ($os) {
        'Windows' => 'php --info | findstr /r /c:"error_log"',
        default => 'php --info | grep error',
    };

    $output = exec($cmd);
    $log_path_regex = '/error_log => (.*)/';
    preg_match($log_path_regex, $output, $matches);

    return $matches[1];
}

function getApiUrl(): string
{
    return 'https://jsonplaceholder.typicode.com/posts';
}
