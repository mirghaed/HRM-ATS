<section id="process" class="ys-section ys-section--alt">
    <div class="ys-container">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">{{ data_get($processSection, 'subtitle', 'فرايند شفاف') }}</span>
                <h2>{{ data_get($processSection, 'title', 'فرايند جذب') }}</h2>
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

            <aside class="ys-process-note" aria-label="اطلاعات تکميلي جذب">
                <h3>زمان پاسخ‌گويي</h3>
                <p>زمان پاسخ اوليه معمولا بين 3 تا 5 روز کاري است.</p>
                <p>نتيجه مراحل از طريق تماس يا پيامک اطلاع‌رساني مي‌شود.</p>
            </aside>
        </div>
    </div>
</section>
