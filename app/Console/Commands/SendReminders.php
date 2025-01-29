<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $now = \Carbon\Carbon::now();
        $futureTime = $now->copy()->addMinutes(15);
        $events = Event::where('start_date', '>=', $now)
        ->where('start_date', '<=', $futureTime)
        ->get();
         dd($events);
        foreach($events as $event){


                foreach($event->registrations as $registration){

                    $mailData = '';
                    Mail::to($registration->volunteer->email)->send(new TestMail($mailData));
                    Sleep(1);
                }



        }






            // Mail::to('josh@marvill.com')->send(new TestMail($mailData));






    }
}
