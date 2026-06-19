<?php

use App\Http\Controllers\HomepageController;
use App\Http\Controllers\QrController;
use App\Livewire\VolunteerRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRegistrationController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\EventBulletinController;
use App\Http\Controllers\VolunteerTimeLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::redirect('/login', '/admin/login');

Route::get('/', [HomepageController::class, 'mainHomepageView'])->name('main.homepage.view');
Route::get('/business-unit/{slug}', [HomepageController::class, 'businessUnitHomepageView'])->name('businessunit.homepage.view');

Route::get('/stories',[ArticleController::class,'viewStories'])->name('stories.view');
Route::get('/article/{slug}',[ArticleController::class,'viewArticle'])->name('article.view');

Route::get('/our-partners', [HomepageController::class, 'ourPartnersView'])->name('ourpartners.view');

Route::get('/volunteer-registration', [VolunteerRegistrationController::class, 'view'])->name('volunteer.form.view');
Route::post('/volunteer-registration-store', [VolunteerRegistrationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('volunteer.form.store');

Route::get('/qr/{event_id}/{attendee_id}', [QrController::class, 'scan_qr'])->name('qr.scan');

// Route::get('/event-modal/{record}', fn ($record) => view('custom.event-modal', ['record' => $record]))->name('event.modal');

Route::get('/registration-confirmation', function () {
    return view('registration-confirmation');
});

Route::get('/email/verify/sent', [VolunteerRegistrationController::class, 'verificationSent'])
    ->name('verification.sent');

Route::get('/data-privacy-policy', function () {
    return view('data-privacy-policy');
})->name('data-privacy-policy');

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');

Route::get('/survey/{survey}/{token}', [SurveyResponseController::class, 'show'])
    ->name('survey.respond');
Route::post('/survey/{survey}', [SurveyResponseController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('survey.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VolunteerRegistrationController::class, 'sendVerificationEmail'])
        ->name('verification.send');

    Route::get('/email/verify/{id}', [VolunteerRegistrationController::class, 'verify'])
        ->name('verification.verify')
        ->middleware('signed');

    Route::get('/volunteer/certificate/{event_id}/{attendee_id}', [PDFController::class, 'generateCertificate'])
        ->name('volunteer.certificate');

    Route::get('/exports/volunteer-list/{event_id}', [ExportController::class, 'exportVolunteer'])
        ->name('volunteer.export');

    Route::post('/volunteer/time-log/{attendee}', [VolunteerTimeLogController::class, 'store'])
        ->name('volunteer.time-log');

    Route::post('/event/{event}/register-slot/{slot}', [EventRegistrationController::class, 'registerSlot'])
        ->name('event.register-slot');
    Route::delete('/event-registration/{registration}/cancel', [EventRegistrationController::class, 'cancelRegistration'])
        ->name('event.cancel-registration');

    Route::post('/events/{event}/bulletins', [EventBulletinController::class, 'store'])
        ->name('event.post-bulletin');
    Route::delete('/events/{event}/bulletins/{bulletin}', [EventBulletinController::class, 'destroy'])
        ->name('event.delete-bulletin');

    Route::post('/admin/events/{event}/toggle-featured', function (\App\Models\Event $event) {
        $user = auth()->user();
        if ($user->hasActiveRole('Facilitator') || $user->hasActiveRole('Volunteer') || !$user->can('set_featured_event')) {
            abort(403);
        }
        $event->is_featured = !$event->is_featured;
        $event->save();
        return response()->json(['featured' => $event->is_featured]);
    })->name('event.toggle-featured');

    Route::post('/admin/events/{event}/duplicate', function (\App\Models\Event $event) {
        $user = auth()->user();
        if (!\App\Filament\Resources\EventResource::canCreate()) {
            abort(403);
        }
        $new = $event->replicate();
        $new->title       = $event->title . ' (Copy)';
        $new->is_featured = false;
        $new->is_published = false;
        $new->save();

        foreach ($event->slots as $slot) {
            $new->slots()->create($slot->only([
                'shift_name', 'slot_type_id', 'start_time', 'end_time',
                'shift_date', 'shift_end_date', 'slot_format',
                'meeting_link', 'total_slots', 'responsibilities',
            ]));
        }

        return response()->json([
            'redirect' => route('filament.admin.resources.events.edit', ['record' => $new->id]),
        ]);
    })->name('event.duplicate');
});


//Test Routes

