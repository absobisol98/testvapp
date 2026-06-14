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
        // [FIX CRITICAL] Only facilitators and admins can post bulletins
        $user = auth()->user();
        $isFacilitatorOfEvent = $event->facilitators()->where('facilitator_id', $user->id)->exists();
        if (!$user->isAdminRole() && !$isFacilitatorOfEvent) {
            abort(403, 'Only event facilitators and admins may post bulletins.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        EventBulletin::create([
            'event_id' => $event->id,
            'posted_by' => $user->id,
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
        // [FIX CRITICAL] Only the poster or admins can delete a bulletin
        $user = auth()->user();
        if (!$user->isAdminRole() && $bulletin->posted_by !== $user->id) {
            abort(403, 'You can only delete bulletins you posted.');
        }

        $bulletin->delete();

        Notification::make()
            ->success()
            ->title('Bulletin Deleted')
            ->body('The bulletin has been deleted successfully.')
            ->send();

        return back();
    }
}
