<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Blog\Post;
use App\Models\BusinessUnit;
use Filament\Notifications\Notification;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class HomepageController extends Controller implements HasMedia
{

    Use InteractsWithMedia;
    
    public function mainHomepageView()
    {
        $opportunities = Event::with('slots')->get();
        $featuredOpportunity = Event::latest()->first();

        $articles = Post::latest()->get();

        $upcoming = Event::with('slots')->whereDate('start_date','>=',now())->orderBy('start_date','desc')->first();
        $ban = $upcoming->getMedia('event-banner-attachments')->first();


        return view('custom.main-landing', compact('opportunities', 'articles', 'featuredOpportunity'));
    }

    public function businessUnitHomepageView($slug)
    {
        $business_unit = BusinessUnit::where('slug', $slug)->first();

        if (!$business_unit) {
            Notification::make()
                ->title('This business unit not found')
                ->icon('far-bell')
                ->danger()
                ->send();

            return redirect()->back();
        }

        // Initialize default values
        $total_volunteers = 0;
        $opportunities = collect();
        $upcoming = null;
        $upcoming_banner = null;
        $socials = collect();
        $website = null;
        $featuredEvents = collect();
        $logo = null;
        $eventCover = null;
        $galleries = [];

        // Get opportunities if they exist
        $opportunities = Event::whereIn('created_by', $business_unit->admins->pluck('id'))->with('slots')->get();

        if ($opportunities->isNotEmpty()) {
            $total_volunteers = $opportunities->sum(function($opp) {
                return $opp->attendees->count();
            });

            $upcoming = Event::whereIn('created_by', $business_unit->admins->pluck('id'))
                ->whereDate('start_date', '>=', now())
                ->orderBy('start_date', 'desc')
                ->first();

            $upcoming_banner = $upcoming?->getBanner();
            $featuredEvents = $opportunities->where('is_featured');
        }

        // Get socials if they exist
        $socials = $business_unit->socials ?? collect();
        $website = $socials->where('social', 'website')->first();

        // Get media if they exist
        if ($business_unit->hasMedia('bu_logo')) {
            $logo = $business_unit->getFirstMediaUrl('bu_logo');
        }

        if ($business_unit->hasMedia('bu_eventcover')) {
            $eventCover = $business_unit->getFirstMediaUrl('bu_eventcover');
        }

        // Get galleries if they exist
        if ($business_unit->hasMedia('bu_galleries')) {
            $galleries = $business_unit->getMedia('bu_galleries')
                ->map(fn($image) => $image->getUrl())
                ->toArray();
            $galleries = array_chunk($galleries, 2);
        }

        return view('custom.business-unit-homepage', compact(
            'opportunities',
            'business_unit',
            'socials',
            'website',
            'logo',
            'eventCover',
            'galleries',
            'upcoming',
            'upcoming_banner',
            'total_volunteers',
            'featuredEvents'
        ));
    }

    public function ourPartnersView(Request $request)
    {

        $partners = BusinessUnit::query()
        ->when($request->search, function($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

    return view('custom.our-partners', compact('partners'));


    }
}
