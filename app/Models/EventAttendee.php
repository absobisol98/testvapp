<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventAttendee
 *
 * @property int $id
 * @property int $event_id
 * @property string $attendee_id
 * @property string|null $facilitator_id
 * @property Carbon|null $time_in
 * @property Carbon|null $time_out
 *
 * @property User|null $user
 * @property Event $event
 *
 * @package App\Models
 */
class EventAttendee extends Model
{
	protected $table = 'event_attendees';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int',
		'time_in' => 'datetime',
		'time_out' => 'datetime'
	];

	protected $fillable = [
		'event_id',
		'attendee_id',
		'facilitator_id',
		'time_in',
		'time_out'
	];

	public function attendee()
	{
		return $this->belongsTo(User::class, 'attendee_id');
	}
	public function facilitator()
	{
		return $this->belongsTo(User::class, 'facilitator_id');
	}

	public function event()
	{
		return $this->belongsTo(Event::class);
	}
}
