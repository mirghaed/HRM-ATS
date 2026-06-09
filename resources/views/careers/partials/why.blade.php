@php
    $benefitIcons = ['analytics', 'growth', 'team', 'learning'];
@endphp

<section id="why-yadak" class="ys-section">
    <div class="ys-container">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">{{ $whyKicker }}</span>
                <h2>{{ $whyTitle }}</h2>
            </div>
        </div>
        <div class="ys-benefit-grid">
            @foreach($benefitItems as $benefit)
                @php
                    $benefitTitle = is_array($benefit) ? data_get($benefit, 'title', 'مزیت همکاری') : 'مزیت همکاری';
                    $benefitText = is_array($benefit) ? data_get($benefit, 'text', '') : $benefit;
                @endphp
                <article class="ys-benefit-card">
                    <span class="ys-benefit-card__icon" aria-hidden="true">
                        <x-career-icon :name="$benefitIcons[$loop->index] ?? 'team'" class="ys-icon-svg" />
                    </span>
                    <h3>{{ $benefitTitle }}</h3>
                    <p>{{ $benefitText }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
