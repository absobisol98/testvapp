@php
    $record = $getRecord();
@endphp
    <div class="rounded overflow-y-hidden h-[120px]">
        <div class="font-bold text-2xl mb-2 truncate event-title"  >{{$record->title}}</div>
        <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">{{\Carbon\Carbon::parse($record->start_date)->format('F d, Y')}}</div>
        <p class="mt-2 text-primary-500 text-sm">{{\Carbon\Carbon::parse($record->start_date)->format('h:i A')}} - {{\Carbon\Carbon::parse($record->end_date)->format('h:i A')}}</p>
        <div class="mt-2 !text-gray-500 truncate event-description">{!! $record->description !!}</div>

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
