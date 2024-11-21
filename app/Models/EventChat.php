<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventChat
 * 
 * @property int $id
 * @property int $event_id
 * @property string|null $message
 * @property string $sender_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Event $event
 * @property User $user
 *
 * @package App\Models
 */
class EventChat extends Model
{
	protected $table = 'event_chats';

	protected $casts = [
		'event_id' => 'int'
	];

	protected $fillable = [
		'event_id',
		'message',
		'sender_id'
	];

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'sender_id');
	}
}
