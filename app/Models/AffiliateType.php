<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AffiliateType
 * 
 * @property int $id
 * @property string $name
 *
 * @package App\Models
 */
class AffiliateType extends Model
{
	protected $table = 'affiliate_types';
	public $timestamps = false;

	protected $fillable = [
		'name'
	];
}
