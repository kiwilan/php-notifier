<?php

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\Utils\NotifierHttpClient;

it('can throw exception', function () {
    $notifier = new Notifier();

    expect(fn () => $notifier->discord('http://github.com'))->toThrow(Exception::class);
    // expect(fn () => $notifier->slack('text'))->not()->toThrow(Exception::class);
    // expect(fn () => NotifierHttpClient::make(null))->not()->toThrow(Exception::class);
});
