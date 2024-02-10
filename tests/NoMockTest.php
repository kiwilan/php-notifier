<?php

use Kiwilan\Notifier\Notifier;

it('can use', function () {
    $discord_webhook = getDotenv('NOTIFIER_DISCORD_WEBHOOK');
    $slack_webhook = getDotenv('NOTIFIER_SLACK_WEBHOOK');
    $notifier = new Notifier();

    $discord = $notifier->discord($discord_webhook)
        ->message('Hello, Discord!')
        ->user('Notifier', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
        ->send();
    expect($discord->isSuccess())->toBeTrue();

    $slack = $notifier->slack($slack_webhook)
        ->message('Hello, Slack!')
        ->send();
    expect($slack->isSuccess())->toBeTrue();
});
