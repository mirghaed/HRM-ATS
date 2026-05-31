<section id="jobs" class="ys-section ys-section--jobs">
    <div class="ys-container" x-ref="jobsSection">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">فرصت‌های شغلی باز</span>
                <h2>موقعیت مناسب خودت را پیدا کن</h2>
            </div>
            <button type="button" class="ys-reset-filter" @click="resetFilters()" x-show="hasActiveFilters" x-cloak>حذف فیلترها</button>
        </div>

        <div class="ys-filter-panel" x-data="{ open: false }">
            <button type="button" class="ys-filter-toggle" @click="open = !open" :aria-expanded="open ? 'true' : 'false'">
                <span>فیلتر فرصت‌ها</span>
                <span class="ys-filter-toggle__icon" :class="open ? 'is-open' : ''">⌄</span>
            </button>

            <div class="ys-filters" :class="open ? 'is-open' : ''" role="search" aria-label="جستجو و فیلتر فرصت‌های شغلی">
                <label class="ys-filter">
                    <span>جستجو</span>
                    <input type="search" x-model.debounce.250ms="filters.q" placeholder="عنوان شغلی یا تیم">
                </label>

                <label class="ys-filter">
                    <span>تیم</span>
                    <select x-model="filters.department">
                        <option value="">همه تیم‌ها</option>
                        @foreach($departments as $department)
                            <option value="{{ \Illuminate\Support\Str::lower($department->slug ?: $department->name) }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="ys-filter">
                    <span>مدل همکاری</span>
                    <select x-model="filters.workMode">
                        <option value="">همه موارد</option>
                        @foreach($workModes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="ys-filter">
                    <span>نوع همکاری</span>
                    <select x-model="filters.employmentType">
                        <option value="">همه موارد</option>
                        @foreach($employmentTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </div>

        @if($jobPositions->isEmpty())
            <div class="ys-empty">
                <span class="ys-empty__icon" aria-hidden="true">
                    <x-career-icon name="filter" class="ys-icon-svg" />
                </span>
                <h3>فعلا فرصت شغلی فعالی منتشر نشده است.</h3>
                <p>رزومه عمومی ثبت کن تا در فرصت‌های بعدی سریع‌تر بررسی شود.</p>
                <a href="#general-apply" class="ys-btn ys-btn--primary">ارسال رزومه عمومی</a>
            </div>
        @else
            <div class="ys-results-bar">
                <p class="ys-results-bar__count" x-text="visibleJobsCount + ' فرصت شغلی مطابق جستجوی شما پیدا شد'"></p>
                <div class="ys-filter-chips" x-show="hasActiveFilters" x-cloak>
                    <template x-if="filters.department">
                        <button type="button" class="ys-filter-chip" @click="filters.department = ''">
                            <span x-text="'تیم: ' + filterLabel('department', filters.department)"></span>
                            <strong aria-hidden="true">×</strong>
                        </button>
                    </template>
                    <template x-if="filters.workMode">
                        <button type="button" class="ys-filter-chip" @click="filters.workMode = ''">
                            <span x-text="'مدل: ' + filterLabel('workMode', filters.workMode)"></span>
                            <strong aria-hidden="true">×</strong>
                        </button>
                    </template>
                    <template x-if="filters.employmentType">
                        <button type="button" class="ys-filter-chip" @click="filters.employmentType = ''">
                            <span x-text="'نوع: ' + filterLabel('employmentType', filters.employmentType)"></span>
                            <strong aria-hidden="true">×</strong>
                        </button>
                    </template>
                </div>
            </div>

            <div class="ys-job-grid" x-ref="jobsGrid">
                @foreach($jobPositions as $job)
                    @php
                        $departmentName = trim((string) ($job->department?->name ?? ''));
                        $departmentKey = \Illuminate\Support\Str::lower($job->department?->slug ?: $departmentName);
                        $workModeLabel = $workModes[$job->work_mode] ?? $job->work_mode;
                        $employmentLabel = $employmentTypes[$job->employment_type] ?? $job->employment_type;
                        $displayTitle = trim((string) $job->title);
                        if ($displayTitle === '' || strcasecmp($displayTitle, 'laravel dev') === 0) {
                            $displayTitle = 'برنامه‌نویس Laravel';
                        }
                        $rawDescription = trim(strip_tags((string) ($job->description ?: $job->requirements ?: '')));
                        $description = ($rawDescription === '' || strtolower($rawDescription) === 'desc')
                            ? 'ما به دنبال فردی هستیم که در توسعه ابزارهای داخلی، بهبود فرایندها و ارتقای تجربه مشتری نقش مستقیمی داشته باشد.'
                            : $rawDescription;
                    @endphp
                    <article
                        data-job-card
                        data-title="{{ \Illuminate\Support\Str::lower($displayTitle) }}"
                        data-department="{{ $departmentKey }}"
                        data-work-mode="{{ $job->work_mode }}"
                        data-employment-type="{{ $job->employment_type }}"
                        x-show="matchCard($el)"
                        x-transition.opacity.duration.200ms
                        class="ys-job-card"
                    >
                        <div class="ys-job-card__head">
                            <h3>{{ $displayTitle }}</h3>
                            <span class="ys-pill ys-pill--danger">{{ $employmentLabel }}</span>
                        </div>
                        <p class="ys-job-card__meta">
                            @if($departmentName !== '')
                                <span>
                                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h16v4H4V4Zm2 6h12v10H6V10Z"/></svg>
                                    {{ $departmentName }}
                                </span>
                            @endif
                            <span>
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 4 8h6l-5 5 2 7-7-4-7 4 2-7-5-5h6l4-8Z"/></svg>
                                {{ $workModeLabel }}
                            </span>
                        </p>
                        @if(($hrmSettings['landing.show_salary'] ?? false) && $job->is_salary_visible_public && $job->salary_min && $job->salary_max)
                            <p class="ys-job-card__salary">حقوق: {{ number_format($job->salary_min) }} تا {{ number_format($job->salary_max) }} {{ $job->salary_currency ?: 'تومان' }}</p>
                        @endif
                        <p class="ys-job-card__desc">{{ \Illuminate\Support\Str::limit($description, 170) }}</p>
                        <p class="ys-job-card__note">زمان پاسخ اولیه: معمولا 3 تا 5 روز کاری</p>
                        <a href="{{ route('careers.jobs.show', $job) }}" class="ys-job-card__cta">مشاهده جزئیات و ارسال رزومه</a>
                    </article>
                @endforeach
            </div>

            <div class="ys-empty" x-show="visibleJobsCount === 0" x-cloak>
                <span class="ys-empty__icon" aria-hidden="true">
                    <x-career-icon name="filter" class="ys-icon-svg" />
                </span>
                <h3>فعلا موقعیتی با این فیلترها نداریم.</h3>
                <p>می‌توانی فیلترها را پاک کنی یا رزومه عمومی بفرستی تا در فرصت‌های بعدی بررسی شود.</p>
                <div class="ys-empty__actions">
                    <button type="button" class="ys-btn ys-btn--secondary" @click="resetFilters()">حذف فیلترها</button>
                    <a href="#general-apply" class="ys-btn ys-btn--primary">ارسال رزومه عمومی</a>
                </div>
            </div>
        @endif
    </div>
</section>

