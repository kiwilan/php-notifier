<?php

use Kiwilan\Notifier\Notifier;

it('can use', function () {
    $webhook = getDotenv('NOTIFIER_SLACK_WEBHOOK');
    $notifier = new Notifier();

    $notifier = $notifier->slack($webhook)
        ->message('Hello, Slack!')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->slack($webhook)
        ->message([
            'Hello',
            'Slack!',
        ])
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->slack($webhook)
        ->attachment('*Hello, Slack!*')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->slack($webhook)
        ->blocks('*Hello, Slack!*')
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();
});

it('can use attachment', function () {
    $webhook = getDotenv('NOTIFIER_SLACK_WEBHOOK');
    $notifier = new Notifier();

    $notifier = $notifier->slack($webhook)
        ->attachment('*Hello, Slack!*')
        ->color('#36a64f')
        ->pretext('Optional pre-text that appears above the attachment block')
        ->author('Bobby Tables', 'http://flickr.com/bobby/')
        ->title('Slack API Documentation', 'https://api.slack.com/')
        ->text('Optional text that appears within the attachment')
        ->fields([
            [
                'name' => 'Priority',
                'value' => 'High',
                'short' => false,
            ],
            [
                'name' => 'Priority',
                'value' => 'High',
            ],
        ])
        ->imageUrl('http://my-website.com/path/to/image.jpg')
        ->footer('Slack API', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->timestamp(new DateTime())
        ->send(mock());
    expect($notifier->isSuccess())->toBeTrue();
});
