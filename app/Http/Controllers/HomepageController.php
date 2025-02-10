<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Blog\Post;
use App\Models\BusinessUnit;
use Filament\Notifications\Notification;

class HomepageController extends Controller
{
    public function mainHomepageView()
    {
        $opportunities = Event::with('slots')->get();
        $featuredOpportunity = Event::latest()->first();

        $articles = Post::latest()->get();



        return view('custom.main-landing', compact('opportunities', 'articles'));
    }

    public function businessUnitHomepageView($slug)
    {

        $business_unit = BusinessUnit::where('slug',$slug)->first();
        if($business_unit){
            $total_volunteers = 0;
            $opportunities = Event::with('slots')->get();
            foreach($opportunities as $opp){
                $total_volunteers+=$opp->attendees->count();
            }

            $upcoming = Event::with('slots')->whereDate('start_date','>=',now())->orderBy('start_date','desc')->first();
            $ban = $upcoming->getMedia('event-banner-attachments')->first();
            $upcoming_banner = ($ban) ? $ban->getUrl() : asset('img/ayala-foundation-bg.jpg');
            $socials =   $business_unit->socials;
            $website = $socials->where('social','website')->first();

            $logo = $business_unit->getMedia('bu_logo')->first();
            if($logo){
                $logo = $logo->getUrl();
            }
            $eventCover = $business_unit->getMedia('bu_eventcover')->first();
            if($eventCover){
                $eventCover = $eventCover->getUrl();
            }
            $images = $business_unit->getMedia('bu_galleries');
            $galleries = array();
            if(!empty($images)){
                foreach($images as $image){
                    array_push( $galleries,$image->getUrl());
                }
            }

            return view('custom.business-unit-homepage', compact('opportunities','business_unit','socials','website','logo','eventCover','galleries','upcoming','upcoming_banner','total_volunteers'));
        }

        Notification::make()
            ->title('This business unit not found')
            ->icon('far-bell')
            ->danger()
            ->send();

        return redirect()->back();
    }
}
