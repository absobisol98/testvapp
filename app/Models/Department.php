<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Department
 *
 * @property int $id
 * @property string $name
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Department extends Model
{
	use SoftDeletes;
	protected $table = 'departments';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}
