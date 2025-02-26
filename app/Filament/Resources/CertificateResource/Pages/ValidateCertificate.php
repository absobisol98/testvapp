<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Resources\CertificateResource;
use App\Models\Certificate;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class ValidateCertificate extends Page implements HasForms
{
    protected static string $resource = CertificateResource::class;
    protected static string $view = 'filament.resources.certificate-resource.pages.validate-certificate';
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('certificate_number')
                    ->required()
                    ->placeholder('Enter certificate number')
                    ->columnSpan('full'),
            ])
            ->statePath('data');
    }

    public function validateCertificate(): void
    {
        $certificate = Certificate::with(['event', 'attendee', 'slot'])
            ->where('certificate_number', $this->data['certificate_number'])
            ->first();

        if (!$certificate) {
            Notification::make()
                ->danger()
                ->title('Invalid Certificate')
                ->body('The certificate number provided is not valid.')
                ->send();
            return;
        }

        Notification::make()
            ->success()
            ->title('Valid Certificate')
            ->body("Certificate issued to {$certificate->attendee->name} for {$certificate->event->title} ({$certificate->slot->name})")
            ->send();
    }
}
