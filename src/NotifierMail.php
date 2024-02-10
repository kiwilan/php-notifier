<?php

namespace Kiwilan\Notifier;

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
    ) {
    }

    public static function make(): self
    {
        return new self();
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
        $this->replyTo = new Address($replyTo, $name);

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
        $this->mailer_transport = Transport::fromDsn("{$this->mailer}://{$this->host}:{$this->port}");
        $this->mailer_instance = new Mailer($this->mailer_transport);

        // $this->logSending("{$this->mailer}://{$this->host}:{$this->port}");

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

        if ($mock) {
            $this->isSuccess = true;

            return $this;
        }

        try {
            $this->mailer_instance->send($this->mailer_email);
        } catch (\Throwable $th) {
            // $this->logError($th->getMessage(), $this->toArray());

            return $this;
        }

        $this->isSuccess = true;
        // $this->logSent();

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
        ];
    }
}
