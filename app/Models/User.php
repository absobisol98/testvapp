<?php

namespace App\Models;

use App\Actions\EventsGetTableQueryAction;
use App\Models\Scopes\FilterVolunteerForExternalAdmin;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use PDO;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;


// #[ScopedBy([FilterVolunteerForExternalAdmin::class])]


class User extends Authenticatable implements FilamentUser, MustVerifyEmail, HasAvatar, HasName, HasMedia
{

    // protected static function booted() : void
    // {
    //     $authUser = auth()->check() :  ?? null;


    //     static::addGlobalScope(new FilterVolunteerForExternalAdmin($authUser));
    // }


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
        return "{$this->firstname} {$this->lastname}";
    }




    // Custom method to generate verification token
    public function generateVerificationToken()
    {
        return hash_hmac('sha256', $this->email, config('app.key'));
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

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
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

    public function eventFacilitator()
    {
        return $this->hasOne(EventFacilitator::class, 'facilitator_id');
    }

    public function getTotalHours()
    {
        $totalHrs = 0;
        foreach($this->eventAttended as $event){
            $totalHrs += $event->get_totalHrs();
        }
        return $totalHrs;
    }
    public function currentBU(){
        if($this->hasRole('External Partner')){
            $id = DB::table('business_unit_has_external_admin')->where('user_id',$this->id)->first();
            if($id){
                return BusinessUnit::find($id->business_unit_id);
            }
        }
        return null;
    }

    public function getBadges()
    {
        $badges = [
            'hours' => [4, 8, 12, 16, 20],
            'opportunities' => [10, 20, 30],
            'streak' => [5],
            'registration' => [1],
            'first_opportunity' => [1]
        ];

        $total_points = 0;
        $earned_badges = [];
        $progress = [];

        // Calculate hours badges
        $total_hours = $this->getTotalHours();
        foreach ($badges['hours'] as $hour_requirement) {
            if ($total_hours >= $hour_requirement) {
                $total_points += 50;
                $earned_badges[] = "Completed {$hour_requirement} hours";
            }
            $progress['hours'] = [
                'current' => $total_hours,
                'next' => $hour_requirement
            ];
        }

        // Calculate opportunities badges
        $total_opportunities = $this->eventAttended()->count();
        foreach ($badges['opportunities'] as $opp_requirement) {
            if ($total_opportunities >= $opp_requirement) {
                $total_points += 50;
                $earned_badges[] = "Completed {$opp_requirement} opportunities";
            }
            $progress['opportunities'] = [
                'current' => $total_opportunities,
                'next' => $opp_requirement
            ];
        }

        // Calculate streak
        $streak = $this->calculateStreak();
        if ($streak >= 5) {
            $total_points += 50;
            $earned_badges[] = "5 consecutive opportunities";
        }
        $progress['streak'] = [
            'current' => $streak,
            'next' => 5
        ];

        // Registration badge
        $total_points += 50; // Everyone gets this for registering
        $earned_badges[] = "Account creation";

        // First opportunity badge
        if ($total_opportunities >= 1) {
            $total_points += 50;
            $earned_badges[] = "First opportunity";
        }

        $ranks = [
            [
                'name' => 'No Rank',
                'required' => 0,
                'pts_required' => 1250,
                'medal' => asset('medals/no-rank.png')
            ],
            [
                'name' => 'Bronze',
                'required' => 1250,
                'pts_required' => 2500,
                'medal' => asset('medals/bronze.png')
            ],
            [
                'name' => 'Silver',
                'required' => 2500,
                'pts_required' => 5000,
                'medal' => asset('medals/silver.png')
            ],
            [
                'name' => 'Gold',
                'required' => 5000,
                'pts_required' => 10000,
                'medal' => asset('medals/gold.png')
            ],
            [
                'name' => 'Platinum',
                'required' => 10000,
                'pts_required' => 10000,
                'medal' => asset('medals/platinum.png')
            ]
        ];

        // Find current rank and next rank based on points
        $current_rank = $ranks[0]; // Default to No Rank
        $next_rank = $ranks[1]; // Default to Bronze

        foreach ($ranks as $index => $rank) {
            if ($total_points >= $rank['required']) {
                $current_rank = $rank;
                $next_rank = isset($ranks[$index + 1]) ? $ranks[$index + 1] : $rank;
            } else {
                // We've found the next rank to achieve
                $next_rank = $rank;
                break;
            }
        }

        return [
            'points' => $total_points,
            'earned_badges' => $earned_badges,
            'progress' => $progress,
            'current_rank' => $current_rank,
            'next_rank' => $next_rank
        ];
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    public function calculateStreak()
    {
        $events = $this->eventAttended()
            ->orderBy('created_at', 'desc')
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->limit(5)
            ->get();

        $streak = 0;
        $previousEventDate = null;

        foreach ($events as $event) {
            $eventDate = Carbon::parse($event->event->start_date);

            // For the first event
            if (!$previousEventDate) {
                $streak++;
                $previousEventDate = $eventDate;
                continue;
            }

            // Check if events are consecutive (within 30 days of each other)
            $daysDifference = $previousEventDate->diffInDays($eventDate);
            if ($daysDifference <= 30) {
                $streak++;
                $previousEventDate = $eventDate;
            } else {
                break; // Break the streak if events are not consecutive
            }
        }

        return $streak;
    }
}
