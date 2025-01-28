<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\User;
class ExportReport extends Component
{
    public function render()
    {

        $users = User::all();

        return view('livewire.export-report')->with('volunteer', $users);
    }
}
