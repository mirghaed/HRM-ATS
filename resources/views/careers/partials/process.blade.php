<section id="process" class="ys-section ys-section--alt">
    <div class="ys-container">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">{{ data_get($processSection, 'subtitle', 'فرایند شفاف') }}</span>
                <h2>{{ data_get($processSection, 'title', 'فرایند جذب') }}</h2>
            </div>
        </div>

        <div class="ys-process-layout ys-process-layout--compact">
            <ol class="ys-process">
                @foreach($processItems as $step)
                    <li>
                        <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3>{{ data_get($step, 'title', 'مرحله') }}</h3>
                            <p>{{ data_get($step, 'desc', '') }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <aside class="ys-process-note" aria-label="اطلاعات تکمیلی جذب">
                <h3>زمان پاسخ‌گویی</h3>
                <p>زمان پاسخ اولیه معمولاً بین ۳ تا ۵ روز کاری است.</p>
                <p>نتیجه مراحل از طریق تماس یا پیامک اطلاع‌رسانی می‌شود.</p>
            </aside>
        </div>
    </div>
</section>
