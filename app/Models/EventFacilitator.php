<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventFacilitator
 * 
 * @property int $id
 * @property int $event_id
 * @property string $facilitator_id
 * 
 * @property Event $event
 * @property User $user
 *
 * @package App\Models
 */
class EventFacilitator extends Model
{
	protected $table = 'event_facilitators';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int'
	];

	protected $fillable = [
		'event_id',
		'facilitator_id'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'facilitator_id');
	}
}
