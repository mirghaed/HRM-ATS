@php
    $departmentCards = $departments->map(function ($department) {
        $name = trim((string) $department->name);
        $desc = trim((string) ($department->description ?? ''));
        if ($desc === '' || strtolower($desc) === 'desc') {
            $desc = 'این تیم روی اهداف کلیدی کسب‌وکار تمرکز دارد و در حال جذب نیروی جدید است.';
        }

        $icon = 'team';
        $key = \Illuminate\Support\Str::of(($department->slug ?: $name))->lower()->ascii()->value();

        if (str_contains($key, 'finance') || str_contains($key, 'account')) {
            $icon = 'wallet';
        } elseif (str_contains($key, 'support') || str_contains($key, 'help')) {
            $icon = 'support';
        } elseif (str_contains($key, 'software') || str_contains($key, 'product') || str_contains($key, 'tech') || str_contains($key, 'dev')) {
            $icon = 'analytics';
        } elseif (str_contains($key, 'sale') || str_contains($key, 'business') || str_contains($key, 'commercial')) {
            $icon = 'job';
        }

        return [
            'name' => $name,
            'description' => $desc,
            'open_positions_count' => (int) ($department->open_positions_count ?? 0),
            'icon' => $icon,
        ];
    })->values();
@endphp

<section id="departments" class="ys-section ys-section--alt">
    <div class="ys-container">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">تیم‌ها و دپارتمان‌ها</span>
                <h2>تیم‌هایی که در {{ $companyName }} کنار هم کار می‌کنند</h2>
            </div>
        </div>

        @if($departmentCards->isNotEmpty())
            <div class="ys-department-grid">
                @foreach($departmentCards as $department)
                    <article class="ys-department-card">
                        <div class="ys-department-card__icon" aria-hidden="true">
                            <x-career-icon :name="data_get($department, 'icon', 'team')" class="ys-icon-svg" />
                        </div>
                        <h3>{{ data_get($department, 'name') }}</h3>
                        <p>{{ data_get($department, 'description') }}</p>

                        @if(data_get($department, 'open_positions_count', 0) > 0)
                            <span>{{ number_format((int) data_get($department, 'open_positions_count')) }} فرصت فعال</span>
                        @else
                            <span class="is-passive">فعلا فرصت فعال ندارد</span>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="ys-empty">
                <span class="ys-empty__icon" aria-hidden="true">
                    <x-career-icon name="team" class="ys-icon-svg" />
                </span>
                <h3>هنوز دپارتمانی برای نمایش ثبت نشده است.</h3>
                <p>از پنل ادمین دپارتمان ایجاد کنید تا در این بخش نمایش داده شود.</p>
            </div>
        @endif
    </div>
</section>

