<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use App\Mail\AnnouncementMail;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class SendMail extends Page implements HasForms
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.send-mail';

    public $name;
    public $email;
    public $filter_type;
    public $subject;
    public $message;
    public $event_id;
    public $company_id;

    protected function getFormActions(): array
    {
        return [
            Action::make('sendmail')
                ->label('Send Email')
                ->color('primary')
                ->submit('sendmail'),
        ];
    }

    protected function getFilterOptions(): array
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'super_admin'])) {
            return [
                '1' => 'All Volunteers',
                '2' => 'Business Unit',
                '3' => 'Specific Event',
            ];
        }

        return [
            '2' => 'My Business Unit',
            '3' => 'My Events',
        ];
    }

    protected function getCompanyOptions(): array
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'super_admin'])) {
            return Company::pluck('name', 'id')->toArray();
        }

        return Company::where('id', $user->company_id)
            ->pluck('name', 'id')
            ->toArray();
    }

    protected function getEventOptions(): array
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'super_admin'])) {
            return Event::pluck('title', 'id')->toArray();
        }

        return Event::query()
            ->join('event_companies', 'events.id', '=', 'event_companies.event_id')
            ->where('event_companies.company_id', $user->company_id)
            ->pluck('events.title', 'events.id')
            ->toArray();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('filter_type')
                            ->label('Send To')
                            ->options(fn () => $this->getFilterOptions())
                            ->reactive()
                            ->extraAttributes([
                                'title' => 'Select receiver in the list'
                            ])
                            ->required(),

                        Grid::make(1)
                            ->schema([
                                Select::make('company_id')
                                    ->label('Business Unit')
                                    ->options(fn () => $this->getCompanyOptions())
                                    ->searchable()
                                    ->hidden(fn (callable $get) => $get('filter_type') !== '2'),

                                Select::make('event_id')
                                    ->label('Event')
                                    ->options(fn () => $this->getEventOptions())
                                    ->searchable()
                                    ->hidden(fn (callable $get) => $get('filter_type') !== '3'),
                            ]),

                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->extraAttributes([
                                'title' => 'Enter mail subject'
                            ]),

                        Textarea::make('message')
                            ->label('Message')
                            ->required()
                            ->extraAttributes([
                                'title' => 'Input message here'
                            ])
                            ->rows(5),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public function sendmail()
    {
        $mail_info = $this->form->getState();
        $user = Auth::user();

        // Validate user has permission to send emails
        if (!$user->hasRole(['admin', 'super_admin'])) {
            if ($mail_info['filter_type'] == 1) {
                // Prevent non-admin users from sending to all volunteers
                Notification::make()
                    ->title('Permission denied')
                    ->danger()
                    ->send();
                return;
            }

            // Validate company access
            if ($mail_info['filter_type'] == 2 && $mail_info['company_id'] != $user->company_id) {
                Notification::make()
                    ->title('Permission denied')
                    ->danger()
                    ->send();
                return;
            }

            // Validate event access
            if ($mail_info['filter_type'] == 3) {
                $eventExists = Event::query()
                    ->join('event_companies', 'events.id', '=', 'event_companies.event_id')
                    ->where('events.id', $mail_info['event_id'])
                    ->where('event_companies.company_id', $user->company_id)
                    ->exists();

                if (!$eventExists) {
                    Notification::make()
                        ->title('Permission denied')
                        ->danger()
                        ->send();
                    return;
                }
            }
        }

        if ($mail_info['filter_type'] == 1) {
            // Existing all volunteers logic
            $volunteers = User::where('volunteer', 1)->get();
        } elseif ($mail_info['filter_type'] == 2) {
            // New company filter logic
            $volunteers = User::where('volunteer', 1)
                ->where('company_id', $mail_info['company_id'])
                ->get();
        } elseif ($mail_info['filter_type'] == 3) {
            // Existing event filter logic
            $volunteers = EventAttendee::where('event_id', $mail_info['event_id'])->get();
        }

        // Send emails based on filter type
        if ($mail_info['filter_type'] == 3) {
            foreach ($volunteers as $volunteer) {
                $volunteer_data = [
                    'volunteer' => $volunteer->attendee,
                    'message' => $mail_info['message'],
                    'subject' => $mail_info['subject'],
                ];

                \Mail::to($volunteer->attendee->email)
                    ->send((new AnnouncementMail($volunteer_data))->subject($mail_info['subject']));
            }
        } else {
            foreach ($volunteers as $volunteer) {
                $volunteer_data = [
                    'volunteer' => $volunteer,
                    'message' => $mail_info['message'],
                    'subject' => $mail_info['subject'],
                ];

                \Mail::to($volunteer->email)
                    ->send((new AnnouncementMail($volunteer_data))->subject($mail_info['subject']));
            }
        }

        Notification::make()
            ->title('Email queued for sending')
            ->success()
            ->send();
    }
}
