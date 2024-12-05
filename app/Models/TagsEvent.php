<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TagsEvent
 * 
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class TagsEvent extends Model
{
	use SoftDeletes;
	protected $table = 'tags_event';

	protected $fillable = [
		'name'
	];
}
