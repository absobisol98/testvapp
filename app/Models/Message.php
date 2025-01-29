<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    //

    protected $fillable = ['message_room_id', 'user_id', 'content'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(MessageRoom::class, 'message_room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
