@php
    $record = $getRecord();
@endphp
    <div class="rounded overflow-y-hidden h-[180px]">
        <div class="font-bold text-2xl mb-2 truncate event-title"  >{{$record->title}}</div>
        <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{\Carbon\Carbon::parse($record->start_date)->format('F d, Y')}}</div>
        <p class="mt-2 text-primary-500 text-sm">{{\Carbon\Carbon::parse($record->start_date)->format('h:i A')}} - {{\Carbon\Carbon::parse($record->end_date)->format('h:i A')}}</p>
        <div>
            <b>Slot: </b>
            @foreach($record->slots as $slot)
                @php
                    $registion_count = $record->registrations->where('slot_type_id',$slot->id)->where('status_id','!=',3)->count();
                @endphp
               <span class="text-sm"><b>({{$slot->total_slots - $registion_count}})</b> {{$slot->type->name}}: {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('h:i')}} - {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->end_time)->format('h:i')}}</span>
            @endforeach
        </div>
        <div class="mt-2 !text-gray-500 no-scrollbar overflow-y-scroll max-h-12 h-12">{!! $record->description !!}</div>

    </div>
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
    $('.event-title').bind('mouseenter', function(){
        var $this = $(this);

        if(this.offsetWidth < this.scrollWidth && !$this.attr('title')){
            $this.attr('x-tooltip.raw', $this.text());
        }
    });
    $('.event-description').bind('mouseenter', function(){
        var $this = $(this);

        if(this.offsetWidth < this.scrollWidth && !$this.attr('description')){
            $this.attr('x-tooltip.raw', $this.text());
        }
    });
</script>
@endpush
