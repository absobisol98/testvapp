<?php

namespace App\Livewire;

use Livewire\Component;

class RoleSwitcher extends Component
{
    public string $pageUrl = '';

    public function mount(): void
    {
        $this->pageUrl = url()->current();
    }

    public function switchRole(string $role): void
    {
        auth()->user()->switchRole($role);

        if ($role === 'Volunteer') {
            $this->redirect(\App\Filament\Resources\VolunteerResource::getUrl('view', ['record' => auth()->id()]));
        } else {
            $this->redirect(url('/admin'));
        }
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
