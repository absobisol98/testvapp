<?php

namespace App\Models;

use App\Actions\EventsGetTableQueryAction;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar, HasName, HasMedia
{
    use InteractsWithMedia;
    use HasUuids, HasRoles;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'firstname',
        'lastname',
        'password',
        'volunteer',
        'middle_name',
        'birthday',
        'is_company',
        'company_name',
        'company_address',
        'company_contact_number',
        'company_representative',
        'company_email',
        'school',
        'school_address',
        'emergency_contact_name',
        'emergency_contact_number',
        'affiliate_type_id',
        'company_id',
        'program_id',
        'cluster_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // if ($panel->getId() === 'admin') {
        //     return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
        // }

        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? null;
    }

    // Define an accessor for the 'name' attribute
    public function getNameAttribute()
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(config('filament-shield.super_admin.name'));
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getFullNameAttribute()
    {
        $middlename = ($this->middle_name) ? $this->middle_name.' ' : '';

        return $this->first_name.' '.$middlename.$this->last_name;

    }

    public function eventAttended()
    {
        return $this->hasMany(EventAttendee::class, 'attendee_id');
    }

    public function getBadges()
    {

        $filteredEvents = (new EventsGetTableQueryAction())->execute($this);
        $filteredEvents = $filteredEvents->orderBy('id','asc')->get()->take(5);

        $events = $this->eventAttended;
        $total_hrs = 0;
        $total_events = $this->eventAttended->count();
        foreach ($events as $evnt){
           $total_hrs = $evnt->get_totalHrs();
        }
        $hours = [16,12,8,4];
        $opportunities = [40,30,20,10];
        $ranks = [
            [
                'name' => 'plat',
                'color'=> '#004d24'
            ],
    
            [
                'name' => 'gold',
                'color'=> '#D4AF37'
            ],
            [
                'name' => 'silver',
                'color'=> '#c0c0c0'
            ],
            [
                'name' => 'bronze',
                'color'=> '#CD7F32'
            ],
        ];
        $mileStone = [
            'first_time' => false,
            'hours' => false,
            'opportuninities' => false,
            'streak' => true,
        ]; 

        foreach($hours as $key => $hr){
            if($total_hrs >= $hr){
                $mileStone['hours'] = $ranks[$key];
                $mileStone['hours']['count'] =$total_hrs;
                $mileStone['hours']['desc'] = 'This volunteer successfully completed '.number_format($total_hrs,2).' Total Hours';
                break;
            }
        }
        $streak = 0;
        foreach($filteredEvents as $latestEvent){
            $test = $latestEvent->attendees->where('attendee_id',$this->id)->first();
            if($test){
                $streak++;
            }
            else{
                $streak = 0;
            }
        }
        if($streak == 5){
            $mileStone['streak'] = true;
        }
        $total_events = 10;
        foreach($opportunities as $key => $opp){
            if($total_events >= $opp){
                $mileStone['opportuninities'] = $ranks[$key];
                $mileStone['opportuninities']['count'] = $total_events;
                $mileStone['opportuninities']['desc'] = 'This volunteer successfully completed '.$total_events.' events.';
                break;
            }
        }

        if($total_events == 1 ){
            $mileStone['first_time'] = true;
        }
        return $mileStone;
    }
}
