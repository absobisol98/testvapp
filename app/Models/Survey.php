<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    //
    protected $fillable = [
        'event_id',
        'title',
        'description',
        'questions',
        'is_anonymous',
    ];

    protected $casts = [
        'questions' => 'array',
        'is_anonymous' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
