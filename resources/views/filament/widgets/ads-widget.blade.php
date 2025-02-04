<x-filament-widgets::widget>

@php
    $today = \Carbon\Carbon::now();
    $categ_id = \App\Models\BannerCategory::where('name', 'Sidebar')->first()?->id;
    $dashboard = \App\Models\BannerCategory::where('name', 'dashboard')->first()?->id;

    $banners = \App\Models\Banner::where('banner_category_id', $categ_id)
                    ->whereDate('start_date','<=', $today )
                    ->whereDate('end_date','>=', $today)
                    ->where('is_visible', 1)
                    ->inRandomOrder()
                    ->first();


    $banners2 = \App\Models\Banner::where('banner_category_id', $dashboard)
                ->whereDate('start_date','<=', $today )
                ->whereDate('end_date','>=', $today)
                ->where('is_visible', 1)
                ->inRandomOrder()
                ->first();


    

    if($banners != null){
        $banner_url = url('/storage/' . $banners?->media[0]->id . '/' . $banners->media[0]->file_name);
    }else{
        $banners_url ='';
        $banners = '';
    }
@endphp
@if (auth()->user()->hasRole('Volunteer'))
    @if ($this->type == 'volunteerDash')
        @if ( $banners2)
            <a href="{{ $banners2->click_url  ?? ''}}"  target="{{ $banners2->click_url_target ?? '_self' }}">
                <div class="max-h-[100px]; " style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <img class="w-full max-h-[300px]"  src="{{url('/storage/' . $banners2?->media[0]->id . '/' . $banners2->media[0]->file_name) ?? ''}}"  alt="">
                </div>
            </a>      
        @endif
    @elseif($this->type == 'side')
        <a href="{{ $banners->click_url  ?? ''}}"  target="{{ $banners->click_url_target ?? '_self' }}">
            <div class="w-full">
                <div class="w-auto " style="max-height: 450px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <img class="w-full h-auto object-cover"style="min-height: 50vh;" src="{{$banner_url ?? ''}}"  alt="">
                </div>
            </div>
        </a>
    @else
    @endif
    
@endif

</x-filament-widgets::widget>
