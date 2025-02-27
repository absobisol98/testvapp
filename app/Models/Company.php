<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Company
 *
 * @property int $id
 * @property string $name
 * @property int|null $cluster_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Cluster|null $cluster
 *
 * @package App\Models
 */
class Company extends Model
{
	use SoftDeletes;
	protected $table = 'companies';

	protected $casts = [
		'cluster_id' => 'int'
	];

	protected $fillable = [
		'name',
		'cluster_id'
	];

	public function cluster()
	{
		return $this->belongsTo(Cluster::class);
	}

    /**
     * Get the events associated with the company through event_companies table
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_companies')
            ->withTimestamps();
    }

    /**
     * Get all event attendees for this company's events
     */
    public function eventAttendees(): HasMany
    {
        return $this->hasMany(EventAttendee::class);
    }
}
