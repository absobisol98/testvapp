<?php

namespace App\Models\Scopes;

use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class FilterVolunteerForExternalAdmin implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {

        if(Auth::hasUser()){
            $user = Auth::user();

            if($user->hasRole('External Partner')){
                $currentBU = $user->currentBU();
                if( $currentBU){
                    $admins = $currentBU->admins->pluck('id');
                    $events = Event::whereIn('created_by',$admins)->pluck('id');
                    $eve_att =  EventAttendee::whereIn('event_id',$events)->pluck('attendee_id');
                    $builder->whereIn('id',$eve_att);
                }
            }
        }
    }
}
