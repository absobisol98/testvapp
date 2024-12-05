<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventApprovalStatus
 * 
 * @property int $id
 * @property string $name
 * 
 * @property Collection|Event[] $events
 *
 * @package App\Models
 */
class EventApprovalStatus extends Model
{
	protected $table = 'event_approval_status';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

	public function events()
	{
		return $this->hasMany(Event::class, 'approval_status_id');
	}
}
