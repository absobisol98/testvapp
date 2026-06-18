<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventAttendee extends Model
{
    protected $table = 'event_attendees';

    protected $fillable = [
        'event_id',
        'attendee_id',
        'slot_type_id',
        'status_id',
        'time_in',
        'time_out',
        'is_approve',
        'is_rejected',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    public function slot()
    {
        return $this->belongsTo(EventSlotType::class, 'slot_type_id');
    }

    public function status()
    {
        return $this->belongsTo(EventRegistrationStatus::class, 'status_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Duration Helpers
    |--------------------------------------------------------------------------
    */

    public function getDurationInMinutes(): int
    {
        if (!$this->time_in || !$this->time_out) {
            return 0;
        }

        return Carbon::parse($this->time_in)
            ->diffInMinutes(Carbon::parse($this->time_out));
    }

    public function getDurationInHours(): float
    {
        return round($this->getDurationInMinutes() / 60, 2);
    }

    /*
    |--------------------------------------------------------------------------
    | Legacy Compatibility
    |--------------------------------------------------------------------------
    |
    | Existing code in User.php calls:
    | $event->get_totalHrs()
    |
    | Keep this method so older code continues working.
    |--------------------------------------------------------------------------
    */

    public function get_totalHrs(): float
    {
        return $this->getDurationInHours();
    }
}