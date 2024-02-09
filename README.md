# **PHP Notifier**

![Banner with british letter box picture in background and Notifier for Laravel title](https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![tests][tests-src]][tests-href]
[![codecov][codecov-src]][codecov-href]

> [!WARNING]
> Not ready for production for now.

PHP Notifier is a package to send notifications for Discord, Slack or mails.

> [!NOTE]
> For Laravel, you can use [`kiwilan/notifier-laravel`](https://github.com/kiwilan/notifier-laravel) package.

> [!IMPORTANT]
> This package does not support push notifications or SMS.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-notifier
```

## Usage

This package offer a support for Discord and Slack webhooks, and emails with `symfony/mailer`.

-   [Discord](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks): support message and rich embeds webhooks.
-   [Slack](https://api.slack.com/messaging/webhooks): support message and attachments webhooks (without legacy API support).
-   Mail: support message and attachments with [`symfony/mailer`](https://symfony.com/doc/current/mailer.html).

### Discord

You can send simple message, with user and avatar. Default user and avatar will be webhook's name and avatar.

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();
$discord = $notifier->discord('https://discord.com/api/webhooks/1234567890/ABCDEFGHIJKLMN0123456789')
    ->message('Hello, Discord!')
    ->user('Notifier', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
    ->send();
```

You can also send rich embeds.

![discord-rich-embed](./docs/discord-rich-embed.jpg)

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();
$discord = $notifier->discord($webhook)
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
    ->send();
```

### Mail

Mail use `symfony/mailer` to send emails.

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();
$mailConfig = $notifier->mail('smtp')
    ->mailer('smtp')
    ->host('mailpit')
    ->port(1025)
    ->username(null)
    ->password(null)
    ->encryption('tls');
```

```php
$mailConfig->from('hello@example.com', 'Hello')
    ->to('to@example.com', 'To')
    ->subject('Hello, Mail!')
    ->message('Hello, Mail!')
    ->html('<h1>Hello, Mail!</h1>')
    ->send();
```

> [!NOTE]
> If `html` is not set, `message` will be used as HTML content.

Multiple recipients can be added with `to` method.

```php
use Symfony\Component\Mime\Address;

$mailConfig->from('hello@example.com', 'Hello')
    ->to([
      new Address('to1@example.com', 'To1'),
      new Address('to2@example.com', 'To2'),
    ])
    ->send();
```

You can add attachments with `addAttachment` method.

```php
$mailConfig->addAttachment('path/to/file.txt', 'file.txt')
    ->send();
```

### Slack

You can send simple message.

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();
$slack = $notifier->slack('https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX')
    ->message('Hello, Slack!')
    ->send();
```

You can also send attachments.

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();
$slack = $notifier->slack($webhook)
    ->attachment('*Hello, Slack!*')
    ->color('#36a64f')
    ->pretext('Optional pre-text that appears above the attachment block')
    ->author('Kiwilan', 'https://github.com/kiwilan')
    ->title('php-notifier', 'https://github.com/kiwilan/php-notifier')
    ->text('Optional text that appears within the attachment')
    ->fields([
        [
            'title' => 'Priority',
            'value' => 'High',
            'short' => false,
        ],
        [
            'title' => 'Priority',
            'value' => 'High',
            'short' => false,
        ],
    ])
    ->imageUrl('https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
    ->footer('Slack API', 'https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
    ->timestamp(new DateTime())
    ->send();
```

### Client

HTTP requests use native stream context to send data, `curl` and `guzzle` can be used as option (default is `stream`).

> [!WARNING]
> If you use `guzzle`, you need to install `guzzlehttp/guzzle` package.

```php
use Kiwilan\Notifier\Notifier;

$notifier = new Notifier();

$stream = $notifier->client('stream') // default
    ->discord($webhook)
    ->message('Hello, Discord!')
    ->send();

$curl = $notifier->client('curl') // use curl instead of stream
    ->discord($webhook)
    ->message('Hello, Discord!')
    ->send();

$guzzle = $notifier->client('guzzle') // use guzzle instead of stream (need guzzlehttp/guzzle package)
    ->discord($webhook)
    ->message('Hello, Discord!')
    ->send();
```

To know if request is successful, you can use `isSuccess` method.

```php
$notifier = new Notifier();

$discord = $notifier->discord($webhook)
    ->message('Hello, Discord!')
    ->send();

if ($discord->isSuccess()) {
    echo 'Message sent!';
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Ewilan Rivière](https://github.com/ewilan-riviere)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[version-src]: https://img.shields.io/packagist/v/kiwilan/php-notifier.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/php-notifier
[php-version-src]: https://img.shields.io/static/v1?style=flat-square&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/php-notifier.svg?style=flat-square&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/php-notifier
[license-src]: https://img.shields.io/github/license/kiwilan/php-notifier.svg?style=flat-square&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/php-notifier/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/php-notifier/run-tests.yml?branch=main&label=tests&style=flat-square&colorA=18181B
[tests-href]: https://github.com/kiwilan/php-notifier/actions/workflows/run-tests.yml
[codecov-src]: https://codecov.io/gh/kiwilan/php-notifier/branch/main/graph/badge.svg?token=n85p0OoBu0
[codecov-href]: https://codecov.io/gh/kiwilan/php-notifier
