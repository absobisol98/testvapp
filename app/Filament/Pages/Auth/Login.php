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
                $this->getEmailFormComponent()->label('Email Address'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Placeholder::make('data_privacy_notice')
                ->content(new HtmlString('<div class="text-sm text-gray-600 mt-1">By logging in, you accept the <a class="text-primary-600 hover:text-primary-700 hover:underline" href="' . route('data-privacy-policy') . '" target="_blank">Data Privacy of Ayala Foundation Inc.</a></div>'))
                ->disableLabel(),
                Placeholder::make('register_link')
                ->content(new HtmlString('<div class="text-center mt-4">Not a Volunteer yet? <a class="text-primary-500 font-bold hover:underline" href="' . route('volunteer.form.view') . '">Register Now</a></div>'))
                ->disableLabel(),
            ]);
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
