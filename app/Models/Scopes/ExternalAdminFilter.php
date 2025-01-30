<?php

namespace App\Models\Scopes;

use App\Filament\Widgets\BusinessUnit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ExternalAdminFilter implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if(auth()->user()->hasRole('External Partner')){
            $currentBU = auth()->user()->currentBU();
            if(  $currentBU){
                $admins = $currentBU->admins->pluck('id');
                $builder->whereIn('created_by',$admins);
            }
        }
    }
}
