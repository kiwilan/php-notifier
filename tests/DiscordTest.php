<?php

use Kiwilan\Notifier\Notifier;

it('can use clients', function () {
    $notifier = new Notifier();
    $webhook = getDotenv('NOTIFIER_DISCORD_WEBHOOK');

    $stream = $notifier->client('stream')
        ->discord($webhook)
        ->message('Hello, Discord!')
        ->send(mock());
    expect($stream->isSuccess())->toBeTrue();
    expect($stream->getRequest()->getMode())->toBe('stream');

    $curl = $notifier->client('curl')
        ->discord($webhook)
        ->message('Hello, Discord!')
        ->send(mock());
    expect($curl->isSuccess())->toBeTrue();
    expect($curl->getRequest()->getMode())->toBe('curl');

    $guzzle = $notifier->client('guzzle')
        ->discord($webhook)
        ->message('Hello, Discord!')
        ->send(mock());
    expect($guzzle->isSuccess())->toBeTrue();
    expect($guzzle->getRequest()->getMode())->toBe('guzzle');

    $curl = $notifier->discord($webhook, 'curl')
        ->message('Hello, Discord!')
        ->send(mock());
    expect($curl->isSuccess())->toBeTrue();
    expect($curl->getRequest()->getMode())->toBe('curl');

    $unknown = $notifier->discord($webhook, 'unknown')
        ->message('Hello, Discord!')
        ->send(mock());
    expect($unknown->isSuccess())->toBeTrue();
    expect($unknown->getRequest()->getMode())->toBe('stream');
});

it('can use', function () {
    $webhook = getDotenv('NOTIFIER_DISCORD_WEBHOOK');

    $notifier = new Notifier();
    $notifier = $notifier->discord($webhook)
        ->message('Hello, Discord!')
        ->user('Notifier', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->discord($webhook)
        ->message([
            'Hello, Discord!',
            'This is a message.',
        ])
        ->user('Notifier', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();
});

it('can use rich embed', function () {
    $webhook = getDotenv('NOTIFIER_DISCORD_WEBHOOK');

    $notifier = new Notifier();
    $notifier = $notifier->discord($webhook)
        ->rich([
            'Rich',
            'simple',
            'for',
            'Discord',
        ])
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->discord($webhook)
        ->rich('Rich advanced')
        ->title('Notifier')
        ->user('Notifier', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->url('https://ewilan-riviere.com')
        ->author('Author', 'https://ewilan-riviere.com', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->color('#3498db')
        ->timestamp()
        ->fields([
            ['name' => 'Field 1', 'value' => 'Value 1'],
            ['name' => 'Field 2', 'value' => 'Value 2'],
        ], inline: true)
        ->thumbnail('https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->image('https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->footer('Footer', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $toArray = $notifier->toArray();
    $toArray['embeds'][0]['timestamp'] = null;
    expect($toArray)->toBe([
        'username' => 'Notifier',
        'avatar_url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
        'embeds' => [
            [
                'author' => [
                    'name' => 'Author',
                    'url' => 'https://ewilan-riviere.com',
                    'icon_url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                ],
                'title' => 'Notifier',
                'url' => 'https://ewilan-riviere.com',
                'type' => 'rich',
                'description' => 'Rich advanced',
                'fields' => [
                    [
                        'name' => 'Field 1',
                        'value' => 'Value 1',
                        'inline' => true,
                    ],
                    [
                        'name' => 'Field 2',
                        'value' => 'Value 2',
                        'inline' => true,
                    ],
                ],
                'color' => 3447003,
                'thumbnail' => [
                    'url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                ],
                'image' => [
                    'url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                ],
                'footer' => [
                    'text' => 'Footer',
                    'icon_url' => 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg',
                ],
                'timestamp' => null,
            ],
        ],
    ]);
});
