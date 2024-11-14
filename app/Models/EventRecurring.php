<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventRecurring
 *
 * @property int $id
 * @property Carbon|null $repeat_until
 * @property string|null $frequency
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property string $created_by
 * @property string|null $updated_by
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property User|null $user
 * @property Collection|Event[] $events
 *
 * @package App\Models
 */
class EventRecurring extends Model
{
	use SoftDeletes;
	protected $table = 'event_recurring';

	protected $casts = [
		'repeat_until' => 'datetime',
		'start_date' => 'datetime',
		'end_date' => 'datetime'
	];

	protected $fillable = [
		'repeat_until',
		'frequency',
		'start_date',
		'end_date',
		'created_by',
		'updated_by'
	];

	public function created_by_user()
	{
		return $this->belongsTo(User::class, 'updated_by');
	}
	public function updated_by_user()
	{
		return $this->belongsTo(User::class, 'created_by');
	}

	public function events()
	{
		return $this->hasMany(Event::class, 'event_recurring_id');
	}
}
