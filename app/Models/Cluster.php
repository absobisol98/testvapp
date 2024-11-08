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
 * Class Cluster
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|Company[] $companies
 *
 * @package App\Models
 */
class Cluster extends Model
{
	use SoftDeletes;
	protected $table = 'clusters';

	protected $fillable = [
		'name'
	];

	public function companies()
	{
		return $this->hasMany(Company::class);
	}
}
