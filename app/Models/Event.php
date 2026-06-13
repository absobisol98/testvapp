<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\Scopes\PublishedEventScope;
use App\Filament\Resources\EventResource\Pages\EventPage;
use App\Models\Scopes\ExternalAdminFilter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\EventBulletin;

/**
 * Class Event
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $event_type_id
 * @property int|null $recurrence_type_id
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property string|null $point_of_contact_id
 * @property int|null $program_id
 * @property string|null $location
 * @property int|null $approval_status_id
 * @property bool|null $sign_up_approval_required
 * @property bool|null $attachment_required
 *
 * @property EventType|null $event_type
 * @property EventApprovalStatus|null $event_approval_status
 * @property User|null $user
 * @property Program|null $program
 * @property EventRecurrenceType|null $event_recurrence_type
 * @property Collection|EventAttendee[] $event_attendees
 * @property Collection|EventChat[] $event_chats
 * @property Collection|EventFacilitator[] $event_facilitators
 * @property Collection|EventRegistration[] $event_registrations
 * @property Collection|EventSlot[] $event_slots
 *
 * @package App\Models
 */


 // scopes

 #[ScopedBy([PublishedEventScope::class])]
 #[ScopedBy([ExternalAdminFilter::class])]


class Event extends Model implements HasMedia
{
    use InteractsWithMedia;
	protected $table = 'events';
	public $timestamps = false;

	protected $casts = [
		'event_type_id' => 'int',
		'recurrence_type_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'registration_end_date' => 'datetime',
		'program_id' => 'int',
		'approval_status_id' => 'int',
		'sign_up_approval_required' => 'bool',
		'attachment_required' => 'bool',
		'is_published' => 'bool',
		'is_featured' => 'bool',
		'is_public' => 'bool',
		'selected_days' => 'array',
	];

	protected $fillable = [
        'event_recurring_id',
        'title',
		'description',
		'event_type_id',
		'recurrence_type_id',
		'start_date',
		'end_date',
		'point_of_contact_id',
		'program_id',
		'location',
		'approval_type',
		'approval_status_id',
		'sign_up_approval_required',
		'attachment_required',
        'frequency',
        'repeat_until',
        'selected_days',
        'monthly_days',
        'monthly_days',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
		'is_published',
		'is_featured',
		'is_public',
		'registration_end_date',
		'event_format',
		'meeting_link',
	];

	public function event_type()
	{
		return $this->belongsTo(EventType::class);
	}

    public function record()
	{
		return $this->belongsTo(EventPage::class);
	}

	public function status()
	{
		return $this->belongsTo(EventApprovalStatus::class, 'approval_status_id');
	}

	public function point_of_contact()
	{
		return $this->belongsTo(User::class, 'point_of_contact_id');
	}

	public function program()
	{
		return $this->belongsTo(Program::class);
	}

	public function event_recurrence_type()
	{
		return $this->belongsTo(EventRecurrenceType::class, 'recurrence_type_id');
	}

	public function attendees()
	{
		return $this->hasMany(EventAttendee::class);
	}

	public function event_chats()
	{
		return $this->hasMany(EventChat::class);
	}

	public function facilitators()
	{
        return $this->BelongsToMany(User::class, 'event_facilitators', 'event_id', 'facilitator_id');
	}

	public function registrations()
	{
		return $this->hasMany(EventRegistration::class);
	}

	public function slots()
	{
		return $this->hasMany(EventSlot::class);
	}

    public function slot_type()
    {
        return $this->belongsTo(EventSlotType::class);
    }


	public function other_fields()
	{
		return $this->hasMany(EventOtherField::class);
	}


    public function created_by_user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'created_by');
    }

    public function updated_by_user(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'updated_by');
    }

    // This gets the parent event
    public function event_recurring()
    {
        return $this->belongsTo(EventRecurring::class, 'event_recurring_id');
    }
    public function companies(): BelongsToMany
    {
        return $this->BelongsToMany(Company::class, 'event_companies', 'event_id', 'company_id')
            ->withTimestamps();
    }
    public function tags(): BelongsToMany
    {
        return $this->BelongsToMany(TagsEvent::class, 'event_tags', 'event_id', 'tag_id');
    }

	public function notifiable()
    {
		$notifiable = array();
		$notifiable[$this->created_by_user->id] = $this->created_by_user;
		foreach($this->facilitators as $facilitator){
			$notifiable[$facilitator->id] = $facilitator;
		}

		foreach(User::role('Ayala Super Admin')->get() as $super_admin){
			$notifiable[$super_admin->id] = $super_admin;
		}
		return $notifiable;
    }

	public function getBanner(){

		$ban = $this->getMedia('event-banner-attachments')->first();
		return ($ban) ? $ban->getUrl() : asset('img/ayala-foundation-bg.jpg');

	}

    public function getAttachment(){

        $attachment = $this->getMedia('event-attachments')->first();
        return ($attachment) ? $attachment->getUrl() : asset('img/ayala-foundation-bg.jpg');
    }

    public function bulletins()
    {
        return $this->hasMany(EventBulletin::class, 'event_id');
    }
}
