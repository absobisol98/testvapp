<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Program
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Program extends Model
{
	use SoftDeletes;
	protected $table = 'programs';

	protected $fillable = [
		'name'
	];

	public function users()
	{
		return $this->hasMany(User::class);
	}


	public function events()
	{
		return $this->hasMany(Event::class);
	}


    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(Volunteer::class, 'program_volunteer', 'program_id', 'volunteer_id')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

}
