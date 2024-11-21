<div class="p-4 flex items-center">
    <div class="h-full pr-4 bg-primary-500 p-2 rounded-lg flex items-center justify-center">
        <div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">{{\Carbon\Carbon::parse($record->start_date)->format('jS')}}</div>
                <p class="text-sm text-white">{{\Carbon\Carbon::parse($record->start_date)->format('F, Y')}}</p>
                <p class="text-4xl font-bold text-white">SLOTS</p>

            </div>
            <div>
                @foreach($record->slots as $slot)
                    @php
                        $registion_count = $record->registrations->where('slot_type_id',$slot->id)->where('status_id','!=',3)->count();
                    @endphp
                    <p class="text-sm text-white"><b>({{$slot->total_slots - $registion_count}})</b> {{$slot->type->name}}: {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('h:i')}} - {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->end_time)->format('h:i')}}</p>
                @endforeach
            </div>
        </div>
    </div>
    <div class="ml-4">
        <div class="font-bold text-2xl mb-2 truncate event-title"  >{{$record->title}}</div>
        <p class="mt-2 text-indigo-500 text-sm">{{\Carbon\Carbon::parse($record->start_date)->format('h:i A')}} - {{\Carbon\Carbon::parse($record->end_date)->format('h:i A')}}</p>
        <div class="mt-2 !text-gray-500 truncate event-description">{!! $record->description !!}</div>
    </div>
</div>
