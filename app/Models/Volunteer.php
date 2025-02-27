<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Scopes\AncientScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

#[ScopedBy([AncientScope::class])]
class Volunteer extends User implements HasMedia
{
    use InteractsWithMedia;
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

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpg', 'image/jpeg', 'image/png', 'image/gif']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
