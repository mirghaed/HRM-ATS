@php
    $resumeFile = $resumeFile ?? $application->files->firstWhere('type', 'resume');
    $isPdf = $resumeFile && (
        str_contains(strtolower((string) $resumeFile->mime_type), 'pdf')
        || str_ends_with(strtolower((string) $resumeFile->original_name), '.pdf')
        || str_ends_with(strtolower((string) $resumeFile->path), '.pdf')
    );
@endphp

@if($resumeFile && $isPdf)
    <div class="ys-resume-viewer">
        <div class="ys-resume-viewer__head">
            <div>
                <h3 class="ys-resume-viewer__title">رزومه پیوست شده</h3>
                <p class="ys-resume-viewer__filename">{{ $resumeFile->original_name ?? basename($resumeFile->path) }}</p>
            </div>
            <a
                href="{{ route('hrm.applications.files.download', [$application, $resumeFile]) }}"
                class="ys-resume-viewer__download"
                download
            >
                دانلود
            </a>
        </div>
        <iframe
            src="{{ route('hrm.applications.files.view', [$application, $resumeFile]) }}"
            class="ys-resume-viewer__frame"
            title="پیش‌نمایش رزومه {{ $application->candidate?->full_name }}"
            loading="lazy"
        ></iframe>
    </div>
@elseif($resumeFile)
    <div class="ys-resume-viewer ys-resume-viewer--fallback">
        <p>پیش‌نمایش مستقیم فقط برای فایل PDF پشتیبانی می‌شود.</p>
        <a href="{{ route('hrm.applications.files.download', [$application, $resumeFile]) }}" class="ys-resume-viewer__download">دانلود فایل</a>
    </div>
@else
    <div class="ys-resume-viewer ys-resume-viewer--empty">
        <p>فایل رزومه‌ای برای نمایش ثبت نشده است.</p>
    </div>
@endif
