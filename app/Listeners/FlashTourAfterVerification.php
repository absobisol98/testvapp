<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class FlashTourAfterVerification
{
    public function handle(Verified $event): void
    {
        session()->flash('vapp_show_tour', true);
    }
}
