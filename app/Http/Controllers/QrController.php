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
                    ->icon('fas-right-to-bracket')
                    ->title('Time in')
                    ->success()
                    ->send();

                Notification::make()
                    ->title("You have been logged in to the event: ".$attendee->event->title)
                    ->icon('fas-right-to-bracket')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->url(route('filament.admin.resources.events.view', ['record' => $attendee->event->id]), shouldOpenInNewTab: true),
                    ])
                    ->sendToDatabase($attendee->attendee);

                return 'time in';

            }elseif($attendee && $attendee->time_in){ //
                $time_in = Carbon::parse($attendee->time_in);

                if($time_in->diffInHours(Carbon::now()) > 1){ // if more than an hour
                    $attendee->time_out = Carbon::now();
                    $attendee->save();

                    Notification::make()
                        ->icon('fas-right-from-bracket')
                        ->title('Time out')
                        ->success()
                        ->send();

                    Notification::make()
                        ->title("You have been logged out of the event: ".$attendee->event->title)
                        ->icon('fas-right-from-bracket')
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->button()
                                ->url(route('filament.admin.resources.events.view', ['record' => $attendee->event->id]), shouldOpenInNewTab: true),
                        ])
                        ->sendToDatabase($attendee->attendee);

                    return 'time out';
                }

                Notification::make()
                    ->title("You have just logged in")
                    ->warning()
                    ->send();

                return "You have just logged in";

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
