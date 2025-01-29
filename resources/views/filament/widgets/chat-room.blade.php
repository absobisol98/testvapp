<x-filament-widgets::widget>
    <div class="border-t p-4 bg-white">
        <form wire:submit="sendMessage" class="flex gap-2">
            <input
                type="text"
                wire:model="messageContent"
                class="flex-1 rounded-lg border-gray-300"
                placeholder="Type your message..."
            >
            <button
                type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"
            >
                Send
            </button>
        </form>
    </div>
</div>
    <div class="flex flex-col h-[500px]">
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            @foreach($this->getMessages() as $message)
                <div class="@if($message->user_id === auth()->id()) ml-auto @endif max-w-3/4">
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="font-semibold text-sm">{{ $message->user->name }}</p>
                                <p class="text-gray-700">{{ $message->content }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $message->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>





</x-filament-widgets::widget>
