@php
    $galleryEnabled = (bool) ($hrmSettings['landing.gallery_enabled'] ?? true);

    $galleryCompanyName = trim((string) ($companyName ?? ($hrmSettings['company.name'] ?? config('app.name', 'Brand'))));

    $defaultSlides = collect(range(1, 24))->map(function (int $i) use ($galleryCompanyName) {
        return [
            'image' => sprintf('/assets/careers/gallery/gallery-%02d.jpg', $i),
            'alt' => 'An Image of '.$galleryCompanyName.' Workspace '.$i,
            'sort_order' => $i * 10,
        ];
    });

    $rawSlides = $hrmSettings['landing.gallery_slides'] ?? [];
    $normalizedSlides = collect(is_array($rawSlides) ? $rawSlides : [])
        ->map(function ($slide, $index) use ($galleryCompanyName) {
            $image = trim((string) data_get($slide, 'image', ''));
            if ($image === '') {
                return null;
            }

            $alt = trim((string) data_get($slide, 'alt', ''));
            if ($alt === '') {
                $alt = 'An Image of '.$galleryCompanyName.' Workspace '.($index + 1);
            }

            $isAbsolute = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
            $imagePath = $isAbsolute ? $image : asset(ltrim($image, '/'));

            return [
                'image' => $imagePath,
                'alt' => $alt,
                'sort_order' => (int) data_get($slide, 'sort_order', ($index + 1) * 10),
            ];
        })
        ->filter()
        ->sortBy('sort_order')
        ->values();

    $gallerySlides = $normalizedSlides->isNotEmpty()
        ? $normalizedSlides->values()
        : $defaultSlides->map(function (array $slide) {
            return [
                ...$slide,
                'image' => asset(ltrim($slide['image'], '/')),
            ];
        })->values();

    // Keep the carousel dense in loop mode while always preserving user-defined slides.
    $targetSlideCount = 24;
    if ($gallerySlides->count() > 0 && $gallerySlides->count() < $targetSlideCount) {
        $base = $gallerySlides->values();
        $expanded = collect();

        for ($i = 0; $i < $targetSlideCount; $i++) {
            $expanded->push($base[$i % $base->count()]);
        }

        $gallerySlides = $expanded->values();
    }
@endphp

@if($galleryEnabled && $gallerySlides->isNotEmpty())
    <section id="gallery" class="ys-gallery-section" aria-hidden="true">
        <div class="ys-gallery-shell">
            <div class="ys-gallery-swiper swiper" dir="rtl" data-ys-gallery-swiper>
                <div class="swiper-wrapper">
                    @foreach($gallerySlides as $slide)
                        <div class="swiper-slide ys-gallery-slide">
                            <img src="{{ $slide['image'] }}" alt="{{ $slide['alt'] }}" loading="lazy" decoding="async" width="700" height="467">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
