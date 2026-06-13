<?php

namespace App\Livewire;

use Livewire\Component;

class RoleSwitcher extends Component
{
    public function switchRole(string $role): void
    {
        auth()->user()->switchRole($role);

        $this->redirect(request()->url(), navigate: false);
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
