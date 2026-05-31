<section class="ys-section">
    <div class="ys-container">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">مسير رشد</span>
                <h2>{{ data_get($growthSection, 'title', 'مسير رشد حرفه‌اي شما') }}</h2>
            </div>
        </div>

        <div class="ys-growth-grid ys-growth-grid--timeline">
            @foreach($growthItems as $growth)
                <article class="ys-growth-card">
                    <span>{{ data_get($growth, 'step', str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT)) }}</span>
                    <h3>{{ data_get($growth, 'title', 'مرحله رشد') }}</h3>
                    <p>{{ data_get($growth, 'desc', '') }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
