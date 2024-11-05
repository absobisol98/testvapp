<?php

namespace App\Filament\Pages;

use App\Models\Volunteer;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\BasePage;

class VolunteerRegistrationPage extends BasePage implements HasForms
{
    use InteractswithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.volunteer-registration-page';

    public ?array $data = [];


    public function mount(): void
    {
        $this->form->fill([]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('First Name')
                ->label('First Name')
                ->required(),

            TextInput::make('Last Name')
                ->label('Last Name')
                ->required(),

            TextInput::make('Work Email')
                ->label('Work Email')
                ->email()
                ->required(),

            TextInput::make('Contact Name')
                ->label('Emergency Contact Name')
                ->required(),

            TextInput::make('Contact Number')
                ->label('Contact Number')
                ->tel()
                ->required(),

        ])
            ->statePath('data');
    }



        public function submit()
        {
            $form = $this->form->getState();

            dd('submitted',$form );
        }
}
