<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Class EventRegistration
 *
 * @property int $id
 * @property int $event_id
 * @property string $volunteer_id
 * @property int $slot_type_id
 * @property bool|null $is_approved
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Event $event
 * @property EventSlot $event_slot
 * @property User $user
 *
 * @package App\Models
 */
class EventRegistration extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'event_registrations';

	protected $casts = [
		'event_id' => 'int',
		'slot_type_id' => 'int',
	];

	protected $fillable = [
		'event_id',
		'volunteer_id',
		'slot_type_id',
		'status_id',
        'message'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function event_slot()
	{
		return $this->belongsTo(EventSlot::class, 'slot_type_id');
	}

	public function volunteer()
	{
		return $this->belongsTo(User::class, 'volunteer_id');
	}

    public function status()
    {
        return $this->belongsTo(EventApprovalStatus::class, 'status_id');
    }
}
