<?php

namespace App\Filament\Resources\MessageRoomResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Message;
use App\Models\MessageRoom;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;

class ChatRoom extends Widget
{
    protected static string $view = 'filament.resources.message-resource.widgets.chat-room';

    public $messageContent = '';

    public MessageRoom $room;

    public $record;

    protected $listeners = [
    'messageReceived' => '$refresh',
    'messageSent' => 'scrollToBottom'
    ];

    public function mount(MessageRoom $room)
    {


        $this->messageRoomId = request()->route('record');

        $this->room = MessageRoom::find($this->record->id);
    }

    protected int | string | array $columnSpan = 'full';


    public function sendMessage()
    {


        // $this->validate([
        //     'messageContent' => 'required|min:1|max:1000',
        // ]);

        try {
            Message::create([
                'message_room_id' => $this->record->id,
                'user_id' => auth()->id(),
                'content' => $this->messageContent,
            ]);


            $recipient = auth()->user();

            $recipient->notify(
                Notification::make()
                    ->title('Saved successfully')
                    ->toDatabase(),
            );

            $this->messageContent = '';
            $this->dispatch('message-sent');

        } catch (\Exception $e) {

            Notification::make()
                ->danger()
                ->title('Error sending message')
                ->send();
        }
    }

    #[On('message-sent')]
    public function refresh()
    {
        $this->dispatch('$refresh');
    }

    protected function getMessages()
    {
        return $this->room->messages()
            ->with('user')
            ->latest()
            ->get();
             // Preserve pagination
             // Reverse order;
    }




}
