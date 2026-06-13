<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MailSettings extends Settings
{
    public string $from_address;
    public string $from_name;
    public ?string $driver;
    public ?string $host;
    public int $port;
    public string $encryption;
    public ?string $username;
    public ?string $password;
    public ?int $timeout;
    public ?string $local_domain;

    public static function group(): string
    {
        return 'mail';
    }

    public static function encrypted(): array
    {
        return [
            'username',
            'password',
        ];
    }

    public function loadMailSettingsToConfig($data = null): void
    {
        // Only override config when the DB setting is non-empty; otherwise keep .env values
        $only = fn ($db, $key) => ($db ?? '') !== '' ? $db : config($key);

        config([
            'mail.mailers.smtp.host'       => $only($data['host']         ?? $this->host,       'mail.mailers.smtp.host'),
            'mail.mailers.smtp.port'       => $only($data['port']         ?? $this->port,       'mail.mailers.smtp.port'),
            'mail.mailers.smtp.encryption' => $only($data['encryption']   ?? $this->encryption, 'mail.mailers.smtp.encryption'),
            'mail.mailers.smtp.username'   => $only($data['username']     ?? $this->username,   'mail.mailers.smtp.username'),
            'mail.mailers.smtp.password'   => $only($data['password']     ?? $this->password,   'mail.mailers.smtp.password'),
            'mail.from.address'            => $only($data['from_address'] ?? $this->from_address, 'mail.from.address'),
            'mail.from.name'               => $only($data['from_name']    ?? $this->from_name,  'mail.from.name'),
        ]);
    }

    /**
     * Check if MailSettings is configured with necessary values.
     */
    public function isMailSettingsConfigured(): bool
    {
        // Check if the essential fields are not null
        return $this->host && $this->username && $this->password && $this->from_address;
    }
}
