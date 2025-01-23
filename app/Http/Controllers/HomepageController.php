<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function mainHomepageView()
    {
        $opportunities = Event::with('slots')->get();
        $featuredOpportunity = Event::latest()->first();

        return view('custom.main-landing', compact('opportunities'));
    }

    public function businessUnitHomepageView()
    {
        $opportunities = Event::with('slots')->get();
        
        return view('custom.business-unit-homepage', compact('opportunities'));
    }
}
