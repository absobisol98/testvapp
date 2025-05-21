<?php

namespace App\Services;

class EmailDomainValidator
{
    /**
     * List of denied domains
     */
    protected array $deniedDomains = [
        // Test/example domains
        'test.com',
        'admin.com',
        'example.com',
        'example.org',
        'example.net',

        // Common disposable email services
        'tempmail.com',
        'throwawaymail.com',
        'mailinator.com',
        'guerrillamail.com',
        'guerrillamail.net',
        'guerrillamail.org',
        'grr.la',
        'sharklasers.com',
        'spam4.me',
        '10minutemail.com',
        '10minutemail.net',
        '10minutemail.org',
        'yopmail.com',
        'yopmail.net',
        'yopmail.fr',
        'cool.fr.nf',
        'jetable.fr.nf',
        'nospam.ze.tc',
        'nomail.xl.cx',
        'tempinbox.com',
        'disposablemail.com',
        'mailnesia.com',
        'mailnull.com',
        'maildrop.cc',
        'trashmail.com',
        'trashmail.net',
        'discard.email',
        'discardmail.com',
        'spamgoes.in',
        'tempemail.net',
        'fakeinbox.com',
        'mailcatch.com',
        'throwawaydomain.com',
        'throwawaymail.com',
        'tempmailer.com',
        'temporarily.de',
        'temporaryinbox.com',
        'mohmal.com',
        'tempail.com',
        'getnada.com',
        'emailondeck.com',
        'dispostable.com',
        'inboxalias.com',
        'tempr.email',
        'fake-mail.net',
        'mailexpire.com',
        'trash-mail.com',
        'temp-mail.org',
        'temp-mail.ru',
        'minuteinbox.com',
        'tempmail.it',
        'throwmail.cc',
        'dropmail.me',
        'spambox.us',
        'spamherelots.com',
        'tempmail.plus',
        'temp-inbox.com',
        'fakemail.net',
        'incognitomail.com',
        'trbvn.com',
        'mailforspam.com',
        'wegwerfemail.de',
        'wegwerfemail.net',
        'wegwerfemail.org',
        'mailinator2.com',
        'maildrop.cc',
        'harakirimail.com',
        'killmail.net',
        'getairmail.com',
        'inboxkitten.com',
        'dispomail.xyz',
        'tempmail.dev',
    ];

    /**
     * Check if a domain is denied
     */
    public function isDenied(string $email): bool
    {
        $domain = $this->extractDomain($email);
        return in_array($domain, $this->deniedDomains);
    }

    /**
     * Extract domain from email address
     */
    protected function extractDomain(string $email): string
    {
        return strtolower(substr(strrchr($email, "@"), 1));
    }
}
