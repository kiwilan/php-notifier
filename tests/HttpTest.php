<?php

use Kiwilan\Notifier\Notifier;
use Kiwilan\Notifier\Utils\NotifierHttpClient;

it('can send sms', function () {
    $url = getDotenv('NOTIFIER_REQUEST');

    $notifier = new Notifier();
    $http = $notifier->http($url)
        ->send(true);
    expect($http->getRequest()->getStatusCode())->toBe(200);
});

it('can use different modes', function () {
    $url = getDotenv('NOTIFIER_REQUEST');

    $http = NotifierHttpClient::make($url)
        ->useStream()
        ->send(true);
    expect($http->getMode())->toBe('stream');

    $http = NotifierHttpClient::make($url)
        ->useCurl()
        ->asJson()
        ->send(true);
    expect($http->getMode())->toBe('curl');
    expect($http->toArray()['json'])->toBeTrue();

    $http = NotifierHttpClient::make($url)
        ->useGuzzle()
        ->asForm()
        ->send(true);
    expect($http->getMode())->toBe('guzzle');
    expect($http->toArray()['json'])->toBeFalse();

    $http = NotifierHttpClient::make(getApiUrl())
        ->client('unknown')
        ->asForm()
        ->send();
    expect($http->getStatusCode())->toBe(201);

    $http = NotifierHttpClient::make(getApiUrl())
        ->useCurl()
        ->send();
    expect($http->getStatusCode())->toBe(201);

    $http = NotifierHttpClient::make(getApiUrl())
        ->useGuzzle()
        ->send();
    expect($http->getStatusCode())->toBe(201);

    expect($http->getMode())->toBe('guzzle');
    expect($http->getUrl())->toBe(getApiUrl());
    expect($http->getRequestMethod())->toBe('POST');
    expect($http->getRequestHeaders())->toBeArray();
    expect($http->getRequestBody())->toBeArray();
    expect($http->getResponseBody())->toBeArray();
    expect($http->getResponseHeaders())->toBeArray();
    expect($http->toArray())->toBeArray();
});
