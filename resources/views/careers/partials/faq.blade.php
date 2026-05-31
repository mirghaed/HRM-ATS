@if($hrmSettings['landing.show_faq'] ?? true)
    <section id="faq" class="ys-section">
        <div class="ys-container">
            <div class="ys-section-head ys-section-head--with-icon">
                <span class="ys-section-head__icon" aria-hidden="true">
                    <x-career-icon name="faq" class="ys-icon-svg" />
                </span>
                <div>
                    <span class="ys-kicker">سوالات متداول</span>
                    <h2>{{ data_get($faqSection, 'title', 'پاسخ به سوالات شما') }}</h2>
                </div>
            </div>
            <div class="ys-faq" x-data="{ openFaq: null }" @keydown.escape.window="openFaq = null">
                @foreach($faqItems as $item)
                    <article class="ys-faq-item">
                        <button
                            type="button"
                            class="ys-faq-item__question"
                            :aria-expanded="openFaq === {{ $loop->index }} ? 'true' : 'false'"
                            @click="openFaq = openFaq === {{ $loop->index }} ? null : {{ $loop->index }}"
                        >
                            <span>{{ data_get($item, 'q') }}</span>
                            <span class="ys-faq-item__icon" :class="openFaq === {{ $loop->index }} ? 'is-open' : ''" x-text="openFaq === {{ $loop->index }} ? '-' : '+'"></span>
                        </button>
                        <div class="ys-faq-item__answer" x-show="openFaq === {{ $loop->index }}" x-transition.opacity.duration.200ms>
                            <p>{{ data_get($item, 'a') }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endif
