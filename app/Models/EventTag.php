<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class EventTag
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class EventTag extends Model
{
    protected $table = 'event_tags';
    public $timestamps = false;

    protected $casts = [
        'event_id' => 'int',
        'tag_id' => 'int'
    ];

    protected $fillable = [
        'event_id',
        'tag_id'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tag()
    {
        return $this->belongsTo(TagsEvent::class);
    }
}
