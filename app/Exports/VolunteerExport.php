<?php

namespace App\Exports;

use App\Models\User;
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

            // dd($event_registrant->volunteer->firstname);
            $collections[] = collect([
                'first_name' => $event_registrant->volunteer->firstname,
                'middle_name' => $event_registrant->volunteer->middle_name,
                'last_name' => $event_registrant->volunteer->lastname,
                'age' => $event_registrant->volunteer->birthday,
                'email' => $event_registrant->volunteer->email,
                'affiliate_type_id' => $event_registrant->volunteer->affiliate_type_id,
                'created_at' => $event_registrant->volunteer->created_at,

            ]);

        }

        return view('filament.exports.volunteer',[
            'collections' => $collections,
        ]);

    }

}
