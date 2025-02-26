<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventBulletin;
use Illuminate\Http\Request;
use Filament\Notifications\Notification;

class EventBulletinController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        EventBulletin::create([
            'event_id' => $event->id,
            'posted_by' => auth()->id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        Notification::make()
            ->success()
            ->title('Bulletin Posted')
            ->body('Your bulletin has been posted successfully.')
            ->send();

        return back();
    }

    public function destroy(Event $event, EventBulletin $bulletin)
    {
        $bulletin->delete();

        Notification::make()
            ->success()
            ->title('Bulletin Deleted')
            ->body('The bulletin has been deleted successfully.')
            ->send();

        return back();
    }
}
