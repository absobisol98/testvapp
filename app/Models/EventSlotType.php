<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventSlotType
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|EventSlot[] $event_slots
 *
 * @package App\Models
 */
class EventSlotType extends Model
{
	protected $table = 'event_slot_types';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function event_slots()
	{
		return $this->hasMany(EventSlot::class, 'slot_type_id');
	}
}
