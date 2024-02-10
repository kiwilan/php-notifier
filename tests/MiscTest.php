<?php

use Kiwilan\Notifier\Utils\NotifierShared;

it('can use shared', function () {
    expect(fn () => NotifierShared::truncate(null))->not()->toThrow(Exception::class);

    $text_2000 = str_repeat('a', 2000);
    $text = NotifierShared::truncate($text_2000);
    expect(strlen($text))->toBe(1983);

    $success = NotifierShared::getShortcutColor('success');
    expect($success)->toBe('22c55e');

    $warning = NotifierShared::getShortcutColor('warning');
    expect($warning)->toBe('eab308');

    $error = NotifierShared::getShortcutColor('error');
    expect($error)->toBe('ef4444');

    $default = NotifierShared::getShortcutColor('default');
    expect($default)->toBe('22c55e');
});
