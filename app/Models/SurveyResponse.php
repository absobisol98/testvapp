<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    //
    protected $fillable = [
        'survey_id',
        'registration_id',
        'answers'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
