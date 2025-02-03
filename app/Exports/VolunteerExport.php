<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\EventRegistration;

class VolunteerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = $this->data;

        $event_registrants = EventRegistration::where('event_id', $data)->get();

        $collections = [];

        foreach ($event_registrants as $event_registrant){

            // dd($event_registrant);
            $collections[] = collect([
                'event' => $event_registrant->event->title,
                'shift' => $event_registrant->event_slot->shift_name,
                'first_name' => $event_registrant->volunteer->firstname,
                'middle_name' => $event_registrant->volunteer->middle_name,
                'last_name' => $event_registrant->volunteer->lastname,
                'age' => $event_registrant->volunteer->birthday,
                'email' => $event_registrant->volunteer->email,
                'affiliate_type_id' => $event_registrant->volunteer->affiliate_type_id,
                'created_at' => $event_registrant->volunteer->created_at,
                'company_name' => $event_registrant->volunteer->company_name,
                'company_address' => $event_registrant->volunteer->company_address,
                'company_contact_number' => $event_registrant->volunteer->company_contact_number,
                'company_representative' => $event_registrant->volunteer->company_representative,
                'school' => $event_registrant->volunteer->school,
                'emergency_contact_name' => $event_registrant->volunteer->emergency_contact_name,
                'emergency_contact_number' => $event_registrant->volunteer->emergency_contact_number,
                'status' => $event_registrant->status->name,







            ]);

        }

        return view('exports.volunteer',[
            'collections' => $collections,
        ]);

    }

}
