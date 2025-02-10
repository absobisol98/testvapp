@php
    $record = $getRecord();
    $banner = $record->media->first();
@endphp
    <div class="rounded">
        @isset($banner)
            <div class="overflow-hidden mb-2 w-full h-[50%]">
                <img src="{{ asset('storage/event-banner-attachments/' . $banner->file_name) }}" alt="">
            </div>
        @else
            <div class="overflow-hidden mb-2 w-full h-[50%] flex items-center justify-center bg-gray-200">
                <img src="{{ asset('img/logo-colored.png') }}" alt="Placeholder Image" class="w-full h-auto">
            </div>
        @endisset
        <div class="font-bold text-2xl mb-2 truncate event-title capitalize"  >{{$record->title}}</div>
        <div class="text-sm">
            <span class="uppercase tracking-wide text-indigo-500 font-semibold">
                {{ \Carbon\Carbon::parse($record->start_date)->format('F d, Y') }}
            </span> |
            <span class="text-primary-500">
                 {{ \Carbon\Carbon::parse($record->start_date)->format('h:i A') }} -
                {{ \Carbon\Carbon::parse($record->end_date)->format('h:i A') }}
            </span>
        </div>
        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4 max-w-[600px]"></div>

        {{-- <div>
            <b>Slot: </b>
            @foreach($record->slots as $slot)
                @php
                    $registion_count = $record->registrations->where('slot_type_id',$slot->id)->where('status_id','!=',3)->count();
                @endphp
               <span class="text-sm"><b>({{$slot->total_slots - $registion_count}})</b> {{$slot->type->name}}: {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('h:i')}} - {{Carbon\Carbon::parse(now()->format('Y-m-d').$slot->end_time)->format('h:i')}}</span>
            @endforeach
        </div> --}}
        {{-- <div class="mt-2 !text-gray-500 custom-scrollbar overflow-y-auto max-h-12 h-12">{!! $record->description !!}</div> --}}
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
