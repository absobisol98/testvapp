<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PublishedEventScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        // Admins and facilitators can see all events (published or draft)
        if ($user && ($user->isAdminRole() || $user->hasActiveRole('Facilitator') || $user->hasActiveRole('External Partner'))) {
            return;
        }

        $builder->where('is_published', true);
    }
}
