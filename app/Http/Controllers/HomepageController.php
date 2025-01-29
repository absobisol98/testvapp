<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Blog\Post;
class HomepageController extends Controller
{
    public function mainHomepageView()
    {
        $opportunities = Event::with('slots')->get();
        $featuredOpportunity = Event::latest()->first();

        $articles = Post::latest()->get();



        return view('custom.main-landing', compact('opportunities', 'articles'));
    }

    public function businessUnitHomepageView()
    {
        $opportunities = Event::with('slots')->get();

        return view('custom.business-unit-homepage', compact('opportunities'));
    }
}
