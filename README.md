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

## Installation

You can install the package via composer:

```bash
composer require kiwilan/php-notifier
```

## Usage

This package offer a support for Discord and Slack webhooks, and emails with `symfony/mailer`.

-   [Discord](https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks): support message and rich embeds webhooks.
-   [Slack](https://api.slack.com/messaging/webhooks): support message, attachments and blocks webhooks (without legacy API support).
-   Mail: support message and attachments with [`symfony/mailer`](https://symfony.com/doc/current/mailer.html).

HTTP requests use native stream context to send data, `curl` and `guzzle` can be used as option (default is `stream`).

> [!WARNING]
> If you use `guzzle`, you need to install `guzzlehttp/guzzle` package.

### Discord

Default webhook URL, username and avatar URL can be set in the config file.

```php
use Kiwilan\Notifier\Facades\Notifier;

$notifier = Notifier::discord('https://discord.com/api/webhooks/1234567890/ABCDEFGHIJKLMN0123456789')
  ->username('Notifier')
  ->avatarUrl('https://raw.githubusercontent.com/kiwilan/php-notifier/main/docs/banner.jpg')
  ->message('Hello, Discord!')
  ->send();
```

### Mail

Mail use `symfony/mailer` to send emails.

```php
use Kiwilan\Notifier\Facades\Notifier;

$notifier = Notifier::mail('smtp')
  ->from('hello@example.com', 'Hello')
  ->to('to@example.com', 'To')
  ->subject('Hello, Mail!')
  ->message('Hello, Mail!')
  ->addAttachment('path/to/file.txt', 'file.txt');
  ->mailer('smtp')
  ->host('mailpit')
  ->port(1025)
  ->username(null)
  ->password(null)
  ->encryption('tls')
  ->send();
```

### Slack

Default webhook URL can be set in the config file.

```php
use Kiwilan\Notifier\Facades\Notifier;

$notifier = Notifier::slack('https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX')
  ->message('Hello, Slack!')
  ->send();
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
