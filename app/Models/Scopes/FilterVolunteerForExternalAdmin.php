<?php

namespace App\Models\Scopes;

use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                $bu = DB::table('business_unit_has_external_admin')->where('user_id',$user->id)->first();
                
                if( $bu){
                    $admins = DB::table('business_unit_has_external_admin')->where('business_unit_id',$bu->business_unit_id)->get()->pluck('user_id');
                    $events = Event::whereIn('created_by',$admins)->pluck('id');
                    $eve_att =  EventAttendee::whereIn('event_id',$events)->pluck('attendee_id');
                    $builder->whereIn('id',$eve_att);
                }
            }
        }
    }
}
