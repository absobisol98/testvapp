<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BasePage;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Placeholder;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Illuminate\Support\HtmlString;

class Login extends BasePage
{

    use HasCustomLayout;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => '',
            'password' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->label('Email'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Placeholder::make('data_privacy_notice')
                ->content(new HtmlString('By logging in, you accept the <a class="underline" href="' . route('data-privacy-policy') . '" target="_blank">Data Privacy Policy</a>.'))
                ->disableLabel(),
            ]);
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
