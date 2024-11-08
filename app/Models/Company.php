<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
