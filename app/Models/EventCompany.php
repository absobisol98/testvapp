<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EventCompany
 * 
 * @property int $id
 * @property int $event_id
 * @property int $company_id
 * 
 * @property Event $event
 * @property Company $company
 *
 * @package App\Models
 */
class EventCompany extends Model
{
	protected $table = 'event_companies';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int',
		'company_id' => 'int'
	];

	protected $fillable = [
		'event_id',
		'company_id'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}
}
