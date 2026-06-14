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
        if ($user->hasActiveRole('Volunteer')) {
            $events = Event::query()
                ->where('is_published', true)
                ->where(function ($query) use ($user) {
                    // Type 4 — Public: visible to everyone
                    $query->where('event_type_id', 4);

                    // Type 1 — Exclusive to Ayala: affiliate_type_id = 1
                    if ($user->affiliate_type_id == 1) {
                        $query->orWhere('event_type_id', 1);
                    }

                    // Type 2 — Exclusive to Business Unit: visible when user's company
                    // is in the event's nominated companies list (admin adds their BU companies
                    // at creation time, same as Hybrid but without Ayala also seeing it)
                    if ($user->company_id) {
                        $query->orWhere(function ($q) use ($user) {
                            $q->where('event_type_id', 2)
                              ->whereHas('companies', function ($cq) use ($user) {
                                  $cq->where('companies.id', $user->company_id);
                              });
                        });

                        // Type 3 — Hybrid: user's company is in the nominated companies list
                        $query->orWhere(function ($q) use ($user) {
                            $q->where('event_type_id', 3)
                              ->whereHas('companies', function ($cq) use ($user) {
                                  $cq->where('companies.id', $user->company_id);
                              });
                        });
                    }
                });
        } else {
            // Admins see all events
            $events = Event::query();
        }

        // For recurring series, show only the next upcoming instance (or most recent past one)
        $events->where(function ($q) {
            $q->whereNull('event_recurring_id')
              ->orWhereIn('events.id', function ($sub) {
                  $sub->selectRaw('
                      COALESCE(
                          (SELECT e2.id FROM events e2
                           WHERE e2.event_recurring_id = events.event_recurring_id
                             AND e2.start_date >= NOW()
                           ORDER BY e2.start_date ASC LIMIT 1),
                          (SELECT e3.id FROM events e3
                           WHERE e3.event_recurring_id = events.event_recurring_id
                           ORDER BY e3.start_date DESC LIMIT 1)
                      )')
                      ->from('events')
                      ->whereNotNull('event_recurring_id')
                      ->groupBy('event_recurring_id');
              });
        });

        return $events;
    }
}
