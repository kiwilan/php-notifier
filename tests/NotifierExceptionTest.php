<?php

use Kiwilan\Notifier\Notifier;

it('can throw exception', function () {
    $notifier = new Notifier();

    expect(fn () => $notifier->discord('http://github.com'))->toThrow(Exception::class);
    expect(fn () => $notifier->slack('text'))->toThrow(Exception::class);
});
