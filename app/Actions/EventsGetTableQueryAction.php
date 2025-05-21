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
        $ayala = Company::where('cluster_id', 1)->pluck('id')->toArray();
        $non_ayala = Company::where('cluster_id', '!=', 1)->pluck('id')->toArray();

        if ($user->hasRole(['Volunteer'])) { // For Volunteer
            $events = Event::query()
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
                        $query->where('event_type_id', 4)->where('event_companies.company_id', $user->company_id); // Hybrid
                    });
                })->select('events.*');
        }elseif ($user->hasRole(['External Partner'])) { // For External Partner
            // Disable global scope if it's uncommented to avoid conflicts
            $events = Event::where(function ($query) use ($user) {
                    // Include events created by users in the same cluster
                    $query->whereHas('created_by_user', function ($subquery) use ($user) {
                        $subquery->where('cluster_id', $user->cluster_id);
                    })
                    // OR include events associated with companies in the same cluster
                    ->orWhereExists(function ($subquery) use ($user) {
                        $subquery->select(\DB::raw(1))
                            ->from('event_companies')
                            ->join('companies', 'companies.id', '=', 'event_companies.company_id')
                            ->whereRaw('event_companies.event_id = events.id')
                            ->where('companies.cluster_id', $user->cluster_id);
                    })
                    // Include public events
                    ->orWhere('event_type_id', 4)
                    // Include Ayala exclusive events (view only)
                    ->orWhere('event_type_id', 1);
                });
        }else {
            // All Events for other roles
            $events = Event::query();
        }

        return $events;
    }
}
