<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ExternalAdminFilter implements Scope
{
    public function apply(Builder $query, Model $model): void
    {
        $user = Auth::user();

        // Only apply filtering for External Partner users
        if ($user && $user->hasRole('External Partner') && $user->cluster_id) {

        
      
            $query->where(function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    // Condition 1: Events created by users with the same cluster_id
                    $q->whereHas('created_by_user', function ($subquery) use ($user) {
                        $subquery->where('cluster_id', $user->cluster_id);
                    });
                })
                ->orWhere(function ($q) use ($user) {
                    // NEW CONDITION: Events associated with companies in the same cluster
                    $q->whereHas('companies', function ($subquery) use ($user) {
                        $subquery->where('companies.cluster_id', $user->cluster_id);
                    });
                })
                ->orWhere(function ($q) {
                    // Condition 2: Events with event_type_id = 4 (visible to all external partners)
                    $q->where('event_type_id', 4);
                })
                ->orWhere(function ($q) {
                    // Condition 3: Events with event_type_id = 1 (view only)
                    $q->where('event_type_id', 1);
                });

            
            });
        }
    }
}
