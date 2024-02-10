<?php

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\NotifierMail;

function createConfig(): NotifierMail
{
    $dotenv = dotenv();
    $notifier = new Notifier();

    return $notifier->mail()
        ->mailer($dotenv['NOTIFIER_MAIL_MAILER'])
        ->host($dotenv['NOTIFIER_MAIL_HOST'])
        ->port($dotenv['NOTIFIER_MAIL_PORT'])
        ->credentials($dotenv['NOTIFIER_MAIL_USERNAME'], $dotenv['NOTIFIER_MAIL_PASSWORD'])
        ->encryption($dotenv['NOTIFIER_MAIL_ENCRYPTION']);
}

it('can use', function () {
    $dotenv = dotenv();

    $notifier = createConfig()
        ->subject('Hello, Mail!')
        ->message('Hello, Mail!')
        ->from($dotenv['NOTIFIER_MAIL_FROM_ADDRESS'], 'Kiwilan')
        ->to($dotenv['NOTIFIER_MAIL_TO_ADDRESS'], 'Kiwilan')
        ->send(mock());

    expect($notifier->isSuccess())->toBeTrue();
});

it('can use attachment', function () {
    $dotenv = dotenv();

    $notifier = createConfig()
        ->subject('Hello, Mail!')
        ->message('Hello, Mail!')
        ->from($dotenv['NOTIFIER_MAIL_FROM_ADDRESS'], 'Kiwilan')
        ->to($dotenv['NOTIFIER_MAIL_TO_ADDRESS'], 'Kiwilan')
        ->addAttachment(__DIR__.'/media/text.md', 'text.md')
        ->send(mock());

    expect($notifier->isSuccess())->toBeTrue();
});
