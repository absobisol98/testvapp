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
        return $this->username  ?? 'No Name';
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

    public function getFullNameAttribute()
    {
        $middlename = ($this->middle_name) ? $this->middle_name.' ' : '';

        return $this->first_name.' '.$middlename.$this->last_name;

    }

    public function eventAttended()
    {
        return $this->hasMany(EventAttendee::class, 'attendee_id');
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

        $filteredEvents = (new EventsGetTableQueryAction())->execute($this);
        $filteredEvents = $filteredEvents->orderBy('id','asc')->get()->take(5);

        $events = $this->eventAttended;
        $total_hrs = 0;
        $total_events = $this->eventAttended->count();
        foreach ($events as $evnt){
           $total_hrs = $evnt->get_totalHrs();
        }
        $points = 0;
        $hours = [4,8,12,16];
        $opportunities = [10,20,30,40];
        $ranks = [
            [
                'name' => 'Bronze',
                'medal'=> asset('medals/bronze.png'),
                'pts_required'=> 0,
            ],

            [
                'name' => 'Silver',
                'medal'=> asset('medals/silver.png'),
                'pts_required'=> 2500,
            ],
            [
                'name' => 'Gold',
                'medal'=> asset('medals/gold.png'),
                'pts_required'=> 5000,
            ],
            [
                'name' => 'Platinum',
                'medal'=> asset('medals/plat.png'),
                'pts_required'=> 10000,
            ],
        ];

        foreach($hours as $key => $hr){
            if($total_hrs >= $hr){
                $points += 50;
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
            $points += 50;
        }
        $total_events = 10;
        foreach($opportunities as $key => $opp){
            if($total_events >= $opp){
                $points += 50;
            }
        }

        if($total_events == 1 ){
            $points += 50;
        }
        $points = 0;

        $cur_rank = null;
        foreach( $ranks as $key => $rank){
            if($rank['pts_required'] <= $points){
                $cur_rank = $key;
            }
        }
        $mileStone['points'] = $points;
        $mileStone['current_rank'] =  ($cur_rank !== null) ? $ranks[$cur_rank] : null;
        if($cur_rank === null){
            $mileStone['next_rank'] = $ranks[0];
        }
        else if($cur_rank == 3){
            $mileStone['next_rank']  = null;
        }else{
            $mileStone['next_rank'] = $ranks[$cur_rank+1];
        }
        return $mileStone;
    }
}
