<x-filament-widgets::widget>

@php
    $today = \Carbon\Carbon::now();
    $categ_id = \App\Models\BannerCategory::where('name', 'Sidebar')->first()->id;
    $banners = \App\Models\Banner::where('banner_category_id', $categ_id)
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
<a href="{{ $banners->click_url  ?? ''}}"  target="{{ $banners->click_url_target ?? '_self' }}">

    <div class="w-full">
        <div class="w-auto " style="min-height: 450px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <img class="w-full h-auto object-cover"style="min-height: 50vh;" src="{{$banner_url ?? ''}}"  alt="">

        </div>
    </div>
    </a>
</x-filament-widgets::widget>
