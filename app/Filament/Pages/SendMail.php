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
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
class SendMail extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.send-mail';



    public $name;
    public $email;
    public $filter_type;
    public $subject;
    public $message;
    public $event_id;



    protected function getFormActions(): array
    {
        return [
            Action::make('sendmail')
                ->label('Send Email')
                ->color('primary')
                ->submit('sendmail'),
        ];
    }

    public function sendmail()
    {



        $mail_info = $this->form->getState();


        if($mail_info['filter_type'] == 1){
            $volunteers = User::where('volunteer', 1)->get();

            foreach($volunteers as $volunteer){



                $volunteer_data = [
                    'volunteer' => $volunteer,
                    'message' => $mail_info['message'],
                    'subject' => $mail_info['subject'],
                ];

                \Mail::to($volunteer->email)
                ->send((new AnnouncementMail($volunteer_data))->subject($mail_info['subject']));

            }
                //!TODO:: Add Business unit filter
        }elseif($mail_info['filter_type'] == 3){



            $volunteers = EventAttendee::where('event_id', $mail_info['event_id'])->get();

            foreach($volunteers as $volunteer){

                $volunteer_data = [
                    'volunteer' => $volunteer->attendee,
                    'message' => $mail_info['message'],
                    'subject' => $mail_info['subject'],
                ];

                \Mail::to($volunteer->attendee->email)
                ->send((new AnnouncementMail($volunteer_data))->subject($mail_info['subject']));

            }

        }


        Notification::make()
        ->title('Email queued for sending')
        ->success()
        ->send();
    }
    public function form(Form $form): Form
    {
    return $form
        ->schema([
            Card::make()
                ->schema([


                    Select::make('filter_type')
                        ->label('Send To')
                        ->options([
                            '1' => 'All Volunteers',
                            '2' => 'Specific Business Unit',
                            '3' => 'Specific Event',
                        ])
                        ->reactive()
                        ->required(),


                        Grid::make(1) // ✅ Grid layout for the selects
                        ->schema([
                            Select::make('business_unit_id') // ✅ Unique field name
                                ->label('Business Unit')
                                ->options(Event::pluck('title', 'id')) // Assuming BusinessUnit model
                                ->searchable()
                                ->hidden(fn (callable $get) => $get('filter_type') !== '2'), // ✅ Show only if "Specific Business Unit" is selected

                            Select::make('event_id')
                                ->label('Event')
                                ->options(Event::pluck('title', 'id'))
                                ->searchable()
                                ->hidden(fn (callable $get) => $get('filter_type') !== '3'), // ✅ Show only if "Specific Event" is selected
                        ]),

                    TextInput::make('subject')
                        ->label('Subject')
                        ->required(),

                    Textarea::make('message')
                        ->label('Message')
                        ->required()
                        ->rows(5),
                ])
                ->columnSpanFull(), // Makes the card span full width
        ]);
    }
}
