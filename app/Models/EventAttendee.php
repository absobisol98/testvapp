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
		'time_out' => 'datetime',
		'updated_at' => 'datetime',
		'is_approve' => 'bool',
		'updated_by' => 'string',
		'encoding_type' => 'int',
		'no_account_name' => 'string',
		'volunteer_count' => 'int',
	];

	protected $fillable = [
		'event_id',
		'attendee_id',
		'facilitator_id',
		'time_in',
		'time_out',
		'updated_at',
		'updated_by',
		'encoding_type',
		'no_account_name',
		'volunteer_count',
		'is_approve'
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

	public function updatedBy()
	{
		return $this->belongsTo(User::class,'updated_by');
	}


	public function get_totalHrs()
	{
		$hrs = 0;
		if($this->time_in && $this->time_out){
			$hrs = $this->time_in->diffInHours($this->time_out);
			//for bulk encoding
			if($this->encoding_type == 3){
				$hrs*=$this->volunteer_count;
			}
		}
		return $hrs;
	}

}
