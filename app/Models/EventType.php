<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventType
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Event[] $events
 *
 * @package App\Models
 */
class EventType extends Model
{
	protected $table = 'event_types';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function events()
	{
		return $this->hasMany(Event::class);
	}
}
