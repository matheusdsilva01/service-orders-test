<?php

namespace App\Services;

use App\Contracts\Mailer;
use Exception;

class NativeMailer implements Mailer
{
    public function __construct(
        private array $config
    )
    {
    }

    private function setup(): void
    {
        ini_set('SMTP', $this->config['host']);

        ini_set(
            'smtp_port',
            (string)$this->config['port']
        );

        ini_set(
            'sendmail_from',
            $this->config['from_address']
        );
    }

    public function send(string $to, string $subject, string $body): bool
    {
        $this->setup();

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            sprintf(
                'From: %s <%s>',
                $this->config['from_name'],
                $this->config['from_address']
            ),
        ];

        try {
            return mail(
                $to,
                $subject,
                $body,
                implode("\r\n", $headers)
            );
        } catch (Exception) {
            return false;
        }
    }
}