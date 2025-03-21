<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\EmailDomainValidator;

class CheckProblematicEmails extends Command
{
    protected $signature = 'emails:check-problematic';
    protected $description = 'Check for users with problematic email domains';

    public function handle()
    {
        $validator = new EmailDomainValidator();
        $problematicUsers = User::get()->filter(function ($user) use ($validator) {
            return $validator->isDenied($user->email);
        });

        if ($problematicUsers->isEmpty()) {
            $this->info('No users with problematic email domains found.');
            return;
        }

        $this->info('Found ' . $problematicUsers->count() . ' users with problematic email domains:');

        $headers = ['ID', 'Name', 'Email', 'Created At'];
        $this->table($headers, $problematicUsers->map(function ($user) {
            return [$user->id, $user->name, $user->email, $user->created_at];
        }));
    }
}
