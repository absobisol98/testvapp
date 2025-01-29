<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventOtherField extends Model
{

    protected $table = 'event_other_fields';
	public $timestamps = false;

	protected $casts = [
		'event_id' => 'int',
        'label' => 'string',
        'text' => 'string',
	];

	protected $fillable = [
		'event_id',
		'label',
        'text'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}
}
