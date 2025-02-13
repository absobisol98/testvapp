<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\AncientScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy([AncientScope::class])]
class Volunteer extends User
{
    protected $table = 'users';

    public function affiliate()
    {
        return $this->belongsTo(AffiliateType::class,'affiliate_type_id');
    }

    public function events(): BelongsToMany
    {
        return $this->BelongsToMany(Event::class, 'event_attendees', 'attendee_id', 'event_id');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_volunteer')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    public function primaryProgram()
    {
        return $this->belongsToMany(Program::class, 'program_volunteer')
                    ->wherePivot('is_primary', true)
                    ->first();
    }

    // Maintain backwards compatibility
    public function getProgramIdAttribute()
    {
        return $this->primaryProgram()?->id;
    }
}
