<?php

use Kiwilan\Notifier\Notifier;

it('can send sms', function () {
    $url = getDotenv('NOTIFIER_REQUEST');

    $notifier = new Notifier();
    $http = $notifier->http($url)
        ->send(true);
    expect($http->getRequest()->getStatusCode())->toBe(200);
});
