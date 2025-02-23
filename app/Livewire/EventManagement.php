<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventAttendee;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Support\Collection;

class EventManagement extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Event $event;

    public function mount(Event $event): void
    {
        $this->event = $event;
    }

    public function table(Table $table): Table
    {

        dd(EventAttendee::query()->where('event_id', $this->event->id)->first()->attendee);
        return $table
            ->query(EventAttendee::query()->where('event_id', $this->event->id))
            ->columns([
                TextColumn::make('volunteer_name')
                    ->label('Volunteer Name')
                    ->formatStateUsing(function ($record) {
                        if ($record->attendee) {
                            return $record->attendee->firstname . ' ' . $record->attendee->lastname;
                        } elseif ($record->encoding_type == 3) {
                            return 'Multiple Attendees';
                        }
                        return $record->no_account_name;
                    }),
                TextColumn::make('time_in')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('time_out')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->formatStateUsing(fn ($record) => number_format($record->get_totalHrs(), 1)),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($record) => $record->is_approve ? 'Approved' : ($record->is_rejected ? 'Rejected' : 'Pending'))
                    ->colors([
                        'success' => 'Approved',
                        'danger' => 'Rejected',
                        'warning' => 'Pending',
                    ])
            ])
            ->actions([
                Action::make('approve')
                    ->visible(fn ($record) => !$record->is_approve && !$record->is_rejected)
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->action(fn ($record) => $this->approveAttendee($record)),
                Action::make('reject')
                    ->visible(fn ($record) => !$record->is_approve && !$record->is_rejected)
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $this->rejectAttendee($record)),
                Action::make('edit_hours')
                    ->form([
                        DateTimePicker::make('time_in')
                            ->required()
                            ->minDate($this->event->start_date)
                            ->maxDate($this->event->end_date),
                        DateTimePicker::make('time_out')
                            ->required()
                            ->minDate($this->event->start_date)
                            ->maxDate($this->event->end_date),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'time_in' => $data['time_in'],
                            'time_out' => $data['time_out'],
                            'updated_by' => auth()->id(),
                        ]);
                    }),
                Action::make('add_without_account')
                    ->form([
                        TextInput::make('name')
                            ->required(),
                        DateTimePicker::make('time_in')
                            ->required(),
                        DateTimePicker::make('time_out')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        EventAttendee::create([
                            'event_id' => $this->event->id,
                            'no_account_name' => $data['name'],
                            'time_in' => $data['time_in'],
                            'time_out' => $data['time_out'],
                            'encoding_type' => 2,
                            'created_by' => auth()->id(),
                        ]);
                    }),
                Action::make('bulk_add')
                    ->form([
                        TextInput::make('count')
                            ->numeric()
                            ->required(),
                        DateTimePicker::make('time_in')
                            ->required(),
                        DateTimePicker::make('time_out')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        EventAttendee::create([
                            'event_id' => $this->event->id,
                            'volunteer_count' => $data['count'],
                            'time_in' => $data['time_in'],
                            'time_out' => $data['time_out'],
                            'encoding_type' => 3,
                            'created_by' => auth()->id(),
                        ]);
                    })
            ])
            ->bulkActions([
                BulkAction::make('bulk_approve')
                    ->label('Approve Selected')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update([
                                'is_approve' => true,
                                'is_rejected' => false,
                                'updated_by' => auth()->id(),
                            ]);
                        });
                    })
                    ->requiresConfirmation(),
                BulkAction::make('bulk_reject')
                    ->label('Reject Selected')
                    ->color('danger')
                    ->action(function (Collection $records) {
                        $records->each(function ($record) {
                            $record->update([
                                'is_approve' => false,
                                'is_rejected' => true,
                                'updated_by' => auth()->id(),
                            ]);
                        });
                    })
                    ->requiresConfirmation(),
            ]);
    }

    private function approveAttendee($record): void
    {
        $record->update([
            'is_approve' => true,
            'is_rejected' => false,
            'updated_by' => auth()->id(),
        ]);
    }

    private function rejectAttendee($record): void
    {
        $record->update([
            'is_approve' => false,
            'is_rejected' => true,
            'updated_by' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.event-management');
    }
}
