<?php

namespace App\Filament\Widgets;

use App\Models\EventAttendee;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopVolunteers extends BaseWidget
{
    public function table(Table $table): Table
    {
        $volunteers = User::role('volunteer')->get();
        dd( $volunteers);
        foreach($volunteers as $test){
            dd($test->eventAtteded);
        }

        return $table
            ->query(
                EventAttendee::query()->groupBy('atte')
            )
            ->columns([
                TextColumn::make('name')->label('Volunteer Name'),
                TextColumn::make('hrs')
                    ->label('Partner'),
            ]);
    }
}
