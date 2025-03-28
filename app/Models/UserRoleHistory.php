<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoleHistory extends Model
{
    protected $fillable = [
        'user_id',
        'previous_role',
        'current_role',
        'can_switch_back',
        'stored_roles'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
