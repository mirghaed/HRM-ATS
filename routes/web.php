<?php

use App\Http\Controllers\HRM\ApplicationController;
use App\Http\Controllers\HRM\ApplicationNoteController;
use App\Http\Controllers\HRM\ApplicationSmsController;
use App\Http\Controllers\HRM\ApplicationSourceController;
use App\Http\Controllers\HRM\ApplicationStatusController;
use App\Http\Controllers\HRM\CandidateController;
use App\Http\Controllers\HRM\DashboardController;
use App\Http\Controllers\HRM\DepartmentController;
use App\Http\Controllers\HRM\HrmSettingController;
use App\Http\Controllers\HRM\ImportController;
use App\Http\Controllers\HRM\InterviewCalendarController;
use App\Http\Controllers\HRM\InterviewController;
use App\Http\Controllers\HRM\JobPositionController;
use App\Http\Controllers\HRM\RecruitmentStatusController;
use App\Http\Controllers\HRM\RecruitmentStatusTransitionController;
use App\Http\Controllers\HRM\RejectionReasonController;
use App\Http\Controllers\HRM\ReportController;
use App\Http\Controllers\HRM\SmsLogController;
use App\Http\Controllers\HRM\SmsTemplateController;
use App\Http\Controllers\HRM\SourceConnectorController;
use App\Http\Controllers\HRM\UserManagementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CaptchaController;
use App\Http\Controllers\Public\CareerController;
use App\Http\Controllers\Public\PublicStorageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/jobs', [CareerController::class, 'jobs'])->name('careers.jobs.index');
Route::get('/careers/jobs/{jobPosition:slug}', [CareerController::class, 'show'])->name('careers.jobs.show');
Route::post('/careers/jobs/{jobPosition:slug}/apply', [CareerController::class, 'apply'])->name('careers.jobs.apply');
Route::post('/careers/general-application', [CareerController::class, 'generalApply'])->name('careers.general.apply');
Route::get('/careers/captcha/image', [CaptchaController::class, 'image'])->name('careers.captcha.image');
Route::get('/media/brand/logos/{filename}', [PublicStorageController::class, 'brandLogo'])->where('filename', '[A-Za-z0-9\.\-]+')->name('brand-logo.show');
Route::get('/media/brand/logos-dark/{filename}', [PublicStorageController::class, 'brandLogoDark'])->where('filename', '[A-Za-z0-9\.\-]+')->name('brand-logo-dark.show');
Route::get('/media/careers/gallery/{filename}', [PublicStorageController::class, 'galleryImage'])->where('filename', '[A-Za-z0-9\.\-]+')->name('careers-gallery.show');
Route::get('/assets/brand/uploads/{filename}', [PublicStorageController::class, 'legacyBrandLogo'])->where('filename', '[A-Za-z0-9\.\-]+')->name('brand-logo.legacy');
Route::get('/assets/careers/gallery/{filename}', [PublicStorageController::class, 'legacyGalleryAsset'])->where('filename', 'gallery-[A-Za-z0-9\.\-]+')->name('careers-gallery.legacy');

Route::get('/hrm/{path}', function (string $path) {
    return redirect('/'.$path, 301);
})->where('path', '.*');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('hrm.')->group(function () {
    Route::resource('departments', DepartmentController::class);
    Route::resource('job-positions', JobPositionController::class);
    Route::resource('application-sources', ApplicationSourceController::class);
    Route::resource('applications', ApplicationController::class);
    Route::get('applications/{application}/files/{file}/view', [ApplicationController::class, 'viewFile'])->name('applications.files.view');
    Route::get('applications/{application}/files/{file}/download', [ApplicationController::class, 'downloadFile'])->name('applications.files.download');

    Route::post('applications/{application}/change-status', [ApplicationStatusController::class, 'change'])->name('applications.change-status');
    Route::post('applications/{application}/notes', [ApplicationNoteController::class, 'store'])->name('applications.notes.store');
    Route::post('applications/{application}/send-sms', [ApplicationSmsController::class, 'send'])->name('applications.send-sms');

    Route::resource('candidates', CandidateController::class)->only(['index', 'show', 'edit', 'update']);

    Route::get('interviews/calendar', [InterviewCalendarController::class, 'index'])->name('interviews.calendar');
    Route::resource('interviews', InterviewController::class);

    Route::resource('sms-templates', SmsTemplateController::class);
    Route::get('sms-logs', [SmsLogController::class, 'index'])->name('sms-logs.index');

    Route::resource('imports', ImportController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('imports/{import}/retry', [ImportController::class, 'retry'])->name('imports.retry');

    Route::resource('source-connectors', SourceConnectorController::class)->except(['show']);
    Route::resource('users', UserManagementController::class)->except(['show']);
    Route::resource('recruitment-statuses', RecruitmentStatusController::class)->except(['show']);
    Route::post('recruitment-status-transitions', [RecruitmentStatusTransitionController::class, 'store'])->name('recruitment-status-transitions.store');
    Route::delete('recruitment-status-transitions/{transition}', [RecruitmentStatusTransitionController::class, 'destroy'])->name('recruitment-status-transitions.destroy');
    Route::resource('rejection-reasons', RejectionReasonController::class)->except(['show']);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('settings', [HrmSettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [HrmSettingController::class, 'update'])->name('settings.update');
    Route::post('settings/gallery-upload', [HrmSettingController::class, 'uploadGalleryImage'])->name('settings.gallery-upload');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
