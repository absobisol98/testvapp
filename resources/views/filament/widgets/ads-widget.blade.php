<x-filament-widgets::widget>
    @php
    $today = \Carbon\Carbon::now();
    $categ_id = \App\Models\BannerCategory::where('name', 'Sidebar')->first()?->id;
    $banners = null;

    if ($categ_id) {
        $banners = \App\Models\Banner::where('banner_category_id', $categ_id)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->where('is_visible', 1)
            ->get();
    }

    $banner_urls = [];

    if ($banners && $banners->count() > 0) {
        foreach ($banners as $banner) {
            if ($banner->media->count() > 0) {
                $banner_urls[] = [
                    'url' => url('/storage/' . $banner->media[0]->id . '/' . $banner->media[0]->file_name),
                    'click_url' => $banner->click_url ?? '',
                    'target' => $banner->click_url_target ?? '_self'
                ];
            }
        }
    }

    if (empty($banner_urls)) {
        $banner_urls[] = [
            'url' => asset('img/ayala-default-sidebar-banner.jpg'),
            'click_url' => '',
            'target' => '_self'
        ];
    }
    @endphp

    <div x-data="{
        banners: {{ json_encode($banner_urls) }},
        currentIndex: 0,
        init() {
            if (this.banners.length > 1) {
                setInterval(() => this.nextBanner(), 6000)
            }
        },
        nextBanner() {
            this.currentIndex = (this.currentIndex + 1) % this.banners.length
        },
        previousBanner() {
            this.currentIndex = this.currentIndex === 0
                ? this.banners.length - 1
                : this.currentIndex - 1
        }
    }" class="relative group">
        <div class="w-full  relative overflow-hidden bg-gray-100 shadow-md" style="min-height:650px; max-height:650px;">
            <template x-for="(banner, index) in banners" :key="index">
                <div x-show="currentIndex === index"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 absolute inset-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0 absolute inset-0"
                     class="absolute inset-0">
                    <a :href="banner.click_url" :target="banner.target" class="block w-full h-full">
                        <img class="w-full h-[450px] min-h-[450px] max-h-[450px] object-cover"
                             :src="banner.url"
                             alt="Banner Image">
                    </a>
                </div>
            </template>


        </div>
    </div>
</x-filament-widgets::widget>
