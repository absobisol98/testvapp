<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $opportunities = Event::with(['slots', 'media'])
            ->where('is_published', true)
            ->whereDate('end_date', '>=', now())
            ->orderBy('created_at', 'Asc')
            ->take(10)
            ->get();
        $featuredOpportunity = Event::with(['slots', 'media', 'program'])
            ->where('is_featured', true)
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->first();

            if (!$featuredOpportunity) {
                $featuredOpportunity = Event::with(['slots', 'media', 'program'])
                    ->where('is_published', true)
                    ->whereDate('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
                    ->first();
            }

        // if (!$featuredOpportunity) {
        //     $featuredOpportunity = Event::with(['slots', 'media'])
        //         ->where('is_published', true)
        //         ->latest()
        //         ->first();
        // }


        $articles = Post::latest()->get();

        // Stats for the impact band
        $statsVolunteers = User::role('volunteer')->count();
        $statsHours = (int) DB::table('event_attendees')
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, time_in, time_out)) as total_hours')
            ->value('total_hours');
        $statsPrograms = Program::count();
        $statsOpen = Event::where('is_published', true)
            ->whereDate('end_date', '>=', now())
            ->count();

        return view('custom.main-landing', compact(
            'opportunities', 'articles', 'featuredOpportunity',
            'statsVolunteers', 'statsHours', 'statsPrograms', 'statsOpen'
        ));
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
