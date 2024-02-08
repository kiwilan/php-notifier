<?php

use Kiwilan\Notifier\Notifier;

it('can use', function () {
    $webhook = dotenv()['NOTIFIER_SLACK_WEBHOOK'];
    $notifier = new Notifier();

    $notifier = $notifier->slack($webhook)
        ->message('Hello, Slack!')
        ->send();
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->slack($webhook)
        ->message([
            'Hello',
            'Slack!',
        ])
        ->send();
    expect($notifier->isSuccess())->toBeTrue();

    $notifier = new Notifier();
    $notifier = $notifier->slack($webhook)
        ->attachment('*Hello, Slack!*')
        ->send();
    expect($notifier->isSuccess())->toBeTrue();

    // $notifier = new Notifier(client: 'curl');
    // $notifier = $notifier->slack($webhook)
    //     ->blocks('*Hello, Slack!*')
    //     ->send();
    // expect($notifier->isSuccess())->toBeTrue();
});
