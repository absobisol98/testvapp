<?php

namespace App\Livewire;

use Livewire\Component;

class RoleSwitcher extends Component
{
    public function switchRole(string $role): void
    {
        auth()->user()->switchRole($role);

        $this->redirect(request()->header('Referer') ?: url('/admin'));
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.role-switcher', [
            'currentRole'    => $user->activeRole(),
            'availableRoles' => $user->availableRoles(),
        ]);
    }
}
