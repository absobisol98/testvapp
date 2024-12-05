<img onerror="this.src='{{url('img/logo-colored.png')}}'; this.onerror=null;"  class="w-full " src="{{$record->getMedia('event-banner-attachments')->first()?->getURL()}}" >
<div class="p-4 flex items-center">
    <div class="h-full pr-4 bg-primary-500 p-2 rounded-lg flex items-center justify-center">

        <div>
            <div class="text-center">
                <div class="text-2xl font-bold text-white">{{\Carbon\Carbon::parse($record->start_date)->format('jS')}}</div>
                <p class="text-sm text-white">{{\Carbon\Carbon::parse($record->start_date)->format('F, Y')}}</p>
            </div>
        </div>
    </div>
    <div class="ml-4">
        <div class="font-bold text-2xl mb-2 truncate event-title"  >{{$record->title}}</div>
        <p class="mt-2 text-indigo-500 text-sm">{{\Carbon\Carbon::parse($record->start_date)->format('h:i A')}} - {{\Carbon\Carbon::parse($record->end_date)->format('h:i A')}}</p>
        <div class="mt-2 !text-gray-500 no-scrollbar overflow-y-scroll max-h-12 h-12">{!! $record->description !!}</div>

{{--        <div class="mt-2 !text-gray-500 truncate event-description">{!! $record->description !!}</div>--}}
    </div>
</div>
