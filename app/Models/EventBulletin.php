<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventBulletin extends Model
{
    protected $fillable = [
        'event_id',
        'posted_by',
        'title',
        'content'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
