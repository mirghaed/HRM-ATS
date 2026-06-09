@extends('layouts.hrm', ['title' => 'رزومه‌ها'])
@section('content')
<div
    class="space-y-4"
    x-data="applicationPreviewModal()"
    @keydown.escape.window="close()"
>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold">رزومه‌ها</h2>
        <a class="rounded-lg bg-cyan-600 px-3 py-2 text-white" href="{{ route('hrm.applications.create') }}">ثبت دستی رزومه</a>
    </div>

    <form class="grid gap-3 md:grid-cols-4" method="get">
        <input class="rounded-xl border-slate-300" name="q" value="{{ request('q') }}" placeholder="جستجو">
        <select class="rounded-xl border-slate-300" name="department_id">
            <option value="">همه دپارتمان‌ها</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
        <select class="rounded-xl border-slate-300" name="status_id">
            <option value="">همه وضعیت‌ها</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>{{ $status->title }}</option>
            @endforeach
        </select>
        <button class="rounded-xl bg-slate-800 px-3 py-2 text-white">فیلتر</button>
    </form>

    <div class="card overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-right">کارجو</th>
                    <th class="px-4 py-3 text-right">موقعیت</th>
                    <th class="px-4 py-3 text-right">کد رهگیری</th>
                    <th class="px-4 py-3 text-right">وضعیت</th>
                    <th class="px-4 py-3 text-right">امتیاز</th>
                    <th class="px-4 py-3 text-right">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $app)
                    @php
                        $resumeFile = $app->files->first();
                        $canPreview = $resumeFile && (
                            str_contains(strtolower((string) $resumeFile->mime_type), 'pdf')
                            || str_ends_with(strtolower((string) ($resumeFile->original_name ?: $resumeFile->path)), '.pdf')
                        );
                    @endphp
                    <tr class="border-t">
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $app->candidate?->full_name }}</div>
                            @if($app->candidate?->mobile)
                                <div class="mt-1 text-xs text-slate-500 ys-ltr-input">{{ $app->candidate->mobile }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $app->jobPosition?->title ?? 'رزومه عمومی' }}</td>
                        <td class="px-4 py-3 font-mono">{{ $app->tracking_code }}</td>
                        <td class="px-4 py-3">{{ $app->currentStatus?->title }}</td>
                        <td class="px-4 py-3">{{ $app->overall_score }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap items-center gap-3">
                                @if($canPreview)
                                    <button
                                        type="button"
                                        class="text-cyan-700"
                                        @click="open(@js([
                                            'id' => $app->id,
                                            'name' => $app->candidate?->full_name,
                                            'title' => $app->jobPosition?->title ?? 'رزومه عمومی',
                                            'department' => $app->department?->name,
                                            'status' => $app->currentStatus?->title,
                                            'trackingCode' => $app->tracking_code,
                                            'mobile' => $app->candidate?->mobile,
                                            'email' => $app->candidate?->email,
                                            'appliedAt' => optional($app->applied_at)->format('Y/m/d H:i'),
                                            'viewUrl' => route('hrm.applications.files.view', [$app, $resumeFile]),
                                            'downloadUrl' => route('hrm.applications.files.download', [$app, $resumeFile]),
                                            'detailUrl' => route('hrm.applications.show', $app),
                                        ]))"
                                    >
                                        مشاهده رزومه
                                    </button>
                                @endif
                                <a class="text-slate-700" href="{{ route('hrm.applications.show', $app) }}">جزئیات</a>
                                @can('delete', $app)
                                    <form method="post" action="{{ route('hrm.applications.destroy', $app) }}" onsubmit="return confirm('رزومه حذف شود؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-700">حذف</button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $applications->links() }}</div>

    <div
        class="ys-application-modal"
        x-show="openModal"
        x-transition.opacity
        x-cloak
        @click.self="close()"
    >
        <div class="ys-application-modal__dialog" @click.stop>
            <div class="ys-application-modal__layout">
                <section class="ys-application-modal__main">
                    <div class="ys-application-modal__head">
                        <div>
                            <h3 class="text-lg font-bold" x-text="active?.name || 'رزومه'"></h3>
                            <p class="mt-1 text-sm text-slate-600">
                                <span x-text="active?.title || ''"></span>
                                <template x-if="active?.department">
                                    <span> - <span x-text="active.department"></span></span>
                                </template>
                            </p>
                        </div>
                        <button type="button" class="ys-application-modal__close" @click="close()" aria-label="بستن">×</button>
                    </div>

                    <div class="ys-application-modal__meta">
                        <span class="badge bg-slate-100 text-slate-700" x-show="active?.trackingCode" x-text="'کد رهگیری: ' + (active?.trackingCode || '')"></span>
                        <span class="badge bg-cyan-100 text-cyan-700" x-show="active?.status" x-text="'وضعیت: ' + (active?.status || '')"></span>
                        <span class="badge bg-slate-100 text-slate-700 ys-ltr-input" x-show="active?.mobile" x-text="active?.mobile"></span>
                        <span class="badge bg-slate-100 text-slate-700 ys-ltr-input" x-show="active?.email" x-text="active?.email"></span>
                    </div>

                    <div class="ys-resume-viewer ys-resume-viewer--modal" x-show="active?.viewUrl">
                        <div class="ys-resume-viewer__head">
                            <div>
                                <h4 class="ys-resume-viewer__title">رزومه پیوست شده</h4>
                            </div>
                            <a
                                :href="active?.downloadUrl || '#'"
                                class="ys-resume-viewer__download"
                                download
                                x-show="active?.downloadUrl"
                            >
                                دانلود
                            </a>
                        </div>
                        <iframe
                            :src="active?.viewUrl || 'about:blank'"
                            class="ys-resume-viewer__frame"
                            title="پیش‌نمایش رزومه"
                        ></iframe>
                    </div>
                </section>

                <aside class="ys-application-modal__sidebar">
                    <div class="ys-application-modal__sidebar-block">
                        <h4>اطلاعات سریع</h4>
                        <dl>
                            <div><dt>تاریخ ارسال</dt><dd x-text="active?.appliedAt || '---'"></dd></div>
                            <div><dt>کد رهگیری</dt><dd x-text="active?.trackingCode || '---'"></dd></div>
                        </dl>
                    </div>
                    <a :href="active?.detailUrl || '#'" class="ys-application-modal__detail-link">مشاهده صفحه جزئیات</a>
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function applicationPreviewModal() {
        return {
            openModal: false,
            active: null,
            open(payload) {
                this.active = payload;
                this.openModal = true;
                document.body.style.overflow = 'hidden';
            },
            close() {
                this.openModal = false;
                this.active = null;
                document.body.style.overflow = '';
            },
        };
    }
</script>
@endpush
