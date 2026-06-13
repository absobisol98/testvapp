<?php

namespace App\Actions;

use App\Models\Company;
use App\Models\Event;
use Zxing\QrReader;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use claviska\SimpleImage;


class EventsGetTableQueryAction
{
    public function execute($user)
    {

       

        $ayala = Company::where('cluster_id',1)->pluck('id')->toArray();
        $non_ayala = Company::where('cluster_id','!=',1)->pluck('id')->toArray();

        if ($user->hasActiveRole('Volunteer')) { // For Volunteer
            $events = Event::query()
                ->where('is_published', true)
                ->leftJoin('event_companies', 'event_companies.event_id', '=', 'events.id')
                ->where(function ($query) use ($ayala, $non_ayala, $user) {
                    $query->where('event_type_id', 4) // Public Events
                        ->orWhere(function ($query) use ($ayala, $non_ayala, $user) {
                            // Exclusive for Ayala
                            if (in_array($user->company_id, $ayala)) {
                                $query->where('event_type_id', 1);
                            }
                            // Exclusive for Business Unit
                            if (in_array($user->company_id, $non_ayala)) {
                                $query->where('event_type_id', 2);
                            }
                        })
                        ->orWhere(function ($query) use ($user) {
                            $query->where('event_type_id', 4)
                                  ->where('event_companies.company_id', $user->company_id);
                        });
                })
                ->select('events.*');
        } else {
            // All Events
            $events = Event::query();

        }

        return $events;
    }
}
