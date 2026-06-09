@php
    $jobsPreviewLimit = $jobsPreviewLimit ?? null;
    $displayJobs = $jobsPreviewLimit ? $jobPositions->take($jobsPreviewLimit) : $jobPositions;
    $hasMoreJobs = $jobsPreviewLimit && $jobPositions->count() > $jobsPreviewLimit;
@endphp

<section id="jobs" class="ys-section ys-section--jobs">
    <div class="ys-container" x-ref="jobsSection">
        <div class="ys-section-head">
            <div>
                <span class="ys-kicker">فرصت‌های شغلی باز</span>
                <h2>{{ $jobsSectionTitle ?? 'موقعیت مناسب خودت را پیدا کن' }}</h2>
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
                <a href="{{ route('careers.index') }}#general-apply" class="ys-btn ys-btn--primary">ارسال رزومه عمومی</a>
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
                @foreach($displayJobs as $job)
                    @include('careers.partials.job-card', ['job' => $job])
                @endforeach
            </div>

            @if($hasMoreJobs)
                <div class="ys-jobs-more">
                    <a href="{{ route('careers.jobs.index') }}" class="ys-btn ys-btn--secondary">مشاهده همه {{ number_format($jobPositions->count()) }} فرصت شغلی</a>
                </div>
            @endif

            <div class="ys-empty" x-show="visibleJobsCount === 0" x-cloak>
                <span class="ys-empty__icon" aria-hidden="true">
                    <x-career-icon name="filter" class="ys-icon-svg" />
                </span>
                <h3>فعلا موقعیتی با این فیلترها نداریم.</h3>
                <p>می‌توانی فیلترها را پاک کنی یا رزومه عمومی بفرستی تا در فرصت‌های بعدی بررسی شود.</p>
                <div class="ys-empty__actions">
                    <button type="button" class="ys-btn ys-btn--secondary" @click="resetFilters()">حذف فیلترها</button>
                    <a href="{{ route('careers.index') }}#general-apply" class="ys-btn ys-btn--primary">ارسال رزومه عمومی</a>
                </div>
            </div>
        @endif
    </div>
</section>
