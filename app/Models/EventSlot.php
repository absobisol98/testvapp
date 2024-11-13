<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventSlot
 *
 * @property int $id
 * @property int $event_id
 * @property int $slot_type_id
 * @property int $total_slots
 *
 * @property Event $event
 * @property EventSlotType $event_slot_type
 * @property Collection|EventRegistration[] $event_registrations
 *
 * @package App\Models
 */
class EventSlot extends Model
{
	protected $table = 'event_slots';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int',
		'slot_type_id' => 'int',
		'total_slots' => 'int'
	];

	protected $fillable = [
		'event_id',
		'slot_type_id',
        'start_time',
        'end_time',
		'total_slots'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function event_slot_type()
	{
		return $this->belongsTo(EventSlotType::class, 'slot_type_id');
	}

	public function event_registrations()
	{
		return $this->hasMany(EventRegistration::class, 'slot_type_id');
	}
}
