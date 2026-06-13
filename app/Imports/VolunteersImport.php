<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
use Spatie\Permission\Models\Role;

class VolunteersImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;

    public function collection(Collection $rows): void
    {
        $volunteerRole = Role::firstOrCreate(['name' => 'Volunteer', 'guard_name' => 'web']);

        foreach ($rows as $row) {
            $email     = trim($row['email'] ?? '');
            $firstName = trim($row['first_name'] ?? $row['firstname'] ?? '');
            $lastName  = trim($row['last_name'] ?? $row['lastname'] ?? '');

            if (empty($email) || empty($firstName)) {
                $this->skipped++;
                continue;
            }

            $volunteerId = trim($row['volunteer_id'] ?? '');
            $skills      = $this->parseArray($row['skills'] ?? '');

            $existing = null;
            if ($volunteerId) {
                $existing = User::where('volunteer_id', $volunteerId)->first();
            }
            if (! $existing) {
                $existing = User::where('email', $email)->first();
            }

            $data = [
                'firstname'                => $firstName,
                'lastname'                 => $lastName,
                'email'                    => $email,
                'volunteer'                => 1,
                'skills'                   => $skills ?: null,
                'emergency_contact_name'   => trim($row['emergency_contact_name'] ?? '') ?: null,
                'emergency_contact_number' => trim($row['emergency_contact_number'] ?? '') ?: null,
                'external_company_name'    => trim($row['company'] ?? '') ?: null,
            ];

            if ($existing) {
                $existing->update($data);
                if (! $existing->hasRole('Volunteer')) {
                    $existing->assignRole($volunteerRole);
                }
                $this->updated++;
            } else {
                $user = User::create(array_merge($data, [
                    'password' => Hash::make(Str::random(16)),
                ]));
                $user->assignRole($volunteerRole);
                $this->created++;
            }
        }
    }

    private function parseArray(string $value): array
    {
        if (empty(trim($value))) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }
}
