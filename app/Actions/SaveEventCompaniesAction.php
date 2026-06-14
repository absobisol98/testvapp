<?php

namespace App\Actions;

use App\Models\BusinessUnit;
use App\Models\EventCompany;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class SaveEventCompaniesAction
{
    public function execute($event, $data, $edit)
    {
        if ($edit) {
            EventCompany::where('event_id', $event->id)->delete();
        }

        // Type 3 (Hybrid) and Type 2 (BU Exclusive) with manually nominated companies
        if (isset($data['companies']) && $data['companies']) {
            foreach ($data['companies'] as $company) {
                EventCompany::firstOrCreate([
                    'event_id'   => $event->id,
                    'company_id' => $company,
                ]);
            }
        }

        // Type 2 — Exclusive to Business Unit: auto-attach the creator's BU company
        // so volunteers from that company can see the event without manual setup.
        if (($data['event_type_id'] ?? null) == 2) {
            $this->attachCreatorBUCompanies($event);
        }
    }

    private function attachCreatorBUCompanies(Event $event): void
    {
        $creator = User::find($event->created_by);
        if (! $creator) {
            return;
        }

        $companyIds = [];

        // Ayala / regular admin: their own company
        if ($creator->company_id) {
            $companyIds[] = $creator->company_id;
        }

        // External Partner admin: company linked to their BU
        $buRow = DB::table('business_unit_has_external_admin')
            ->where('user_id', $creator->id)
            ->first();

        if ($buRow) {
            $bu = BusinessUnit::find($buRow->business_unit_id);
            if ($bu && $bu->company_id) {
                $companyIds[] = $bu->company_id;
            }
        }

        foreach (array_unique($companyIds) as $companyId) {
            EventCompany::firstOrCreate([
                'event_id'   => $event->id,
                'company_id' => $companyId,
            ]);
        }
    }
}
