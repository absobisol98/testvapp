<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Tables\Columns\TotalHrsColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopVolunteers extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                // ...
            )
            ->columns([
                // TotalHrsColumn::make('total')
                // ...
            ]);
    }
}
