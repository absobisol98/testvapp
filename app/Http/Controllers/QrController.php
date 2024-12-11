<?php

namespace App\Http\Controllers;

use App;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\QrCode;
use App\Models\QrCodeClaimedLog;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function scan_qr($event_id,$attendee_id)
    {
        $facilitator_ids = Event::where('id',$event_id)->first()->facilitators()->get()->pluck('id')->toArray();


        if (User::role('super_admin')->first() || in_array(auth()->id(),$facilitator_ids)){ // if Super Admin or Facilitator
            $attendee = EventAttendee::where('event_id',$event_id)->where('attendee_id',$attendee_id)->first();

            if($attendee && !$attendee->time_in){ // Not yet logged in
                $attendee->time_in = Carbon::now();
                $attendee->save();

                Notification::make()
                    ->title('Time in')
                    ->success()
                    ->send();

                return 'time in';

            }elseif($attendee && $attendee->time_in){ //
                $attendee->time_out = Carbon::now();
                $attendee->save();

                Notification::make()
                    ->title('Time out')
                    ->success()
                    ->send();

                return 'time out';
            }else{
                Notification::make()
                    ->title('Invalid QR')
                    ->success()
                    ->send();

                return 'invalid QR';
            }
        }else{

            Notification::make()
                ->title('Unauthorized Facilitator')
                ->warning()
                ->send();

            return 'unauthorized facilitator';
        }
    }
}
