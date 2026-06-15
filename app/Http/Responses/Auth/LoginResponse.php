<?php

namespace App\Http\Responses\Auth;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse
    {
        return redirect(url('/admin'));
    }
}
