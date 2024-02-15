<?php

namespace Kiwilan\Notifier;

use Closure;
use Kiwilan\Notifier\Utils\NotifierShared;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class NotifierMail extends Notifier
{
    /**
     * @param  Address[]  $to  Array of `Address` object
     * @param  array{
     *  mailer: string,
     *  host: string,
     *  port: int,
     *  encryption: string,
     *  username: string,
     *  password: string,
     *  from: Address,
     *  to: Address[],
     *  subject: string
     * }  $autoConfig
     */
    protected function __construct(
        protected ?string $mailer = null,
        protected ?string $host = null,
        protected ?int $port = null,
        protected ?string $encryption = null,
        protected ?string $username = null,
        protected ?string $password = null,
        protected ?TransportInterface $mailer_transport = null,
        protected ?Email $mailer_email = null,
        protected ?Mailer $mailer_instance = null,
        protected array $to = [],
        protected ?Address $from = null,
        protected ?Address $replyTo = null,
        protected ?string $subject = null,
        protected ?string $message = null,
        protected ?string $html = null,
        protected array $attachments = [],
        protected bool $isSuccess = false,

        protected array $autoConfig = [],
        protected ?Closure $logSending = null,
        protected ?Closure $logError = null,
        protected ?Closure $logSent = null,
    ) {
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * Set auto config to complete the missing properties.
     *
     * @param  array{
     *  mailer: string,
     *  host: string,
     *  port: int,
     *  encryption: string,
     *  username: string,
     *  password: string,
     *  from: Address,
     *  to: Address[],
     *  subject: string
     * }  $autoConfig
     */
    public function autoConfig(array $autoConfig): self
    {
        $this->autoConfig = $autoConfig;

        return $this;
    }

    public function logSending(Closure $closure): self
    {
        $this->logSending = $closure;

        return $this;
    }

    public function logError(Closure $closure): self
    {
        $this->logError = $closure;

        return $this;
    }

    public function logSent(Closure $closure): self
    {
        $this->logSent = $closure;

        return $this;
    }

    private function parseAutoConfig(): self
    {
        if (! $this->mailer && $this->autoConfig['mailer']) {
            $this->mailer = $this->autoConfig['mailer'];
        }

        if (! $this->host && $this->autoConfig['host']) {
            $this->host = $this->autoConfig['host'];
        }

        if (! $this->port && $this->autoConfig['port']) {
            $this->port = intval($this->autoConfig['port']);
        }

        if (! $this->encryption && $this->autoConfig['encryption']) {
            $this->encryption = $this->autoConfig['encryption'];
        }

        if (! $this->username && $this->autoConfig['username']) {
            $this->username = $this->autoConfig['username'];
        }

        if (! $this->password && $this->autoConfig['password']) {
            $this->password = $this->autoConfig['password'];
        }

        if (! $this->from && $this->autoConfig['from']) {
            $this->from = $this->autoConfig['from'];
        }

        if (count($this->to) === 0 && $this->autoConfig['to']) {
            $this->to = $this->autoConfig['to'];
        }

        if (! $this->subject && $this->autoConfig['subject']) {
            $this->subject = $this->autoConfig['subject'];
        }

        return $this;
    }

    /**
     * @param  string  $mailer  Mailer transport, default `smtp`
     */
    public function mailer(string $mailer): self
    {
        $this->mailer = $mailer;

        return $this;
    }

    /**
     * @param  string  $host  Mailer host, default `mailpit`
     */
    public function host(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param  int  $port  Mailer port, default `1025`
     */
    public function port(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param  string  $encryption  Mailer encryption, default `tls`
     */
    public function encryption(string $encryption): self
    {
        $this->encryption = $encryption;

        return $this;
    }

    public function credentials(string $username, string $password): self
    {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * @param  Address[]|string  $to  Array of `Address` object
     * @param  string|null  $name  Useful if `$to` is a string
     */
    public function to(array|string $to, ?string $name = null): self
    {
        if (is_string($to)) {
            $to = [new Address($to, $name)];
        }

        $this->to = $to;

        return $this;
    }

    public function from(string $from, ?string $name = null): self
    {
        $this->from = new Address($from, $name ?? '');

        return $this;
    }

    public function replyTo(string $replyTo, ?string $name = null): self
    {
        $this->replyTo = new Address($replyTo, $name ?? '');

        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function message(array|string $message): self
    {
        $this->message = NotifierShared::arrayToString($message);

        return $this;
    }

    /**
     * @param  string|string[]  $html
     */
    public function html(array|string $html): self
    {
        $this->html = NotifierShared::arrayToString($html);

        return $this;
    }

    /**
     * Add attachment to the email.
     *
     * @param  string  $path  File path
     * @param  string|null  $name  File name
     */
    public function addAttachment(string $path, ?string $name = null): self
    {
        $this->attachments[] = [
            'path' => $path,
            'name' => $name,
        ];

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function send(bool $mock = false): self
    {
        $this->parseAutoConfig();

        $this->mailer_transport = Transport::fromDsn("{$this->mailer}://{$this->host}:{$this->port}");
        $this->mailer_instance = new Mailer($this->mailer_transport);

        $this->mailer_email = (new Email())
            ->to(...$this->to)
            ->from($this->from);

        if ($this->replyTo) {
            $this->mailer_email->replyTo($this->replyTo);
        }

        if ($this->subject) {
            $this->mailer_email->subject($this->subject);
        }

        if ($this->message) {
            $this->mailer_email->text($this->message);
        }

        if ($this->html) {
            $this->mailer_email->html($this->html);
        }

        if (! $this->html) {
            $this->mailer_email->html($this->message);
        }

        if (! $this->message && $this->html) {
            $this->mailer_email->text(strip_tags($this->html));
        }

        if (! $this->message && ! $this->html) {
            $this->mailer_email->text('');
            $this->mailer_email->html('');
        }

        if (count($this->attachments) > 0) {
            foreach ($this->attachments as $attachment) {
                $this->mailer_email->attachFromPath($attachment['path'], $attachment['name']);
            }
        }

        if ($this->logSending) {
            ($this->logSending)($this->toArray());
        }

        if ($mock) {
            $this->isSuccess = true;

            return $this;
        }

        try {
            $this->mailer_instance->send($this->mailer_email);
        } catch (\Throwable $th) {
            if ($this->logError) {
                ($this->logError)($th->getMessage(), $this->toArray());
            } else {
                NotifierShared::logError($th->getMessage(), $this->toArray());
            }

            return $this;
        }

        $this->isSuccess = true;
        if ($this->logSent) {
            ($this->logSent)($this->toArray());
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'mailer' => $this->mailer,
            'host' => $this->host,
            'port' => $this->port,
            'encryption' => $this->encryption,
            'username' => $this->username,
            'password' => $this->password,
            'to' => $this->to,
            'from' => $this->from,
            'replyTo' => $this->replyTo,
            'subject' => $this->subject,
            'message' => $this->message,
            'html' => $this->html,
            'attachments' => $this->attachments,
            'isSuccess' => $this->isSuccess,
            'autoConfig' => $this->autoConfig,
        ];
    }
}
