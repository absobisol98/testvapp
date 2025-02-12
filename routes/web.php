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


Route::get('/', [HomepageController::class, 'mainHomepageView'])->name('main.homepage.view');
Route::get('/business-unit/{slug}', [HomepageController::class, 'businessUnitHomepageView'])->name('businessunit.homepage.view');

Route::get('/stories',[ArticleController::class,'viewStories'])->name('stories.view');
Route::get('/article/{slug}',[ArticleController::class,'viewArticle'])->name('article.view');

Route::get('/our-partners', [HomepageController::class, 'ourPartnersView'])->name('ourpartners.view');

Route::get('/volunteer-registration', [VolunteerRegistrationController::class, 'view'])->name('volunteer.form.view');
Route::post('/volunteer-registration-store', [VolunteerRegistrationController::class, 'store'])->name('volunteer.form.store');

Route::get('/qr/{event_id}/{attendee_id}', [QrController::class, 'scan_qr'])->name('qr.scan');

// Route::get('/event-modal/{record}', fn ($record) => view('custom.event-modal', ['record' => $record]))->name('event.modal');

Route::get('/exports/volunteer-list/{event_id}' , [ExportController::class, 'exportVolunteer'])->name('volunteer.export');

Route::get('/registration-confirmation', function () {
    return view('registration-confirmation');
});

Route::get('/email/verify/sent', [VolunteerRegistrationController::class, 'verificationSent'])
    ->name('verification.sent');

Route::get('/data-privacy-policy', function () {
    return view('data-privacy-policy');
})->name('data-privacy-policy');


Route::get('/survey/{survey}/{token}', [SurveyResponseController::class, 'show'])
    ->name('survey.respond');
Route::post('/survey/{survey}', [SurveyResponseController::class, 'store'])
    ->name('survey.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [VolunteerRegistrationController::class, 'sendVerificationEmail'])
        ->name('verification.send');

    Route::get('/email/verify/{id}', [VolunteerRegistrationController::class, 'verify'])
        ->name('verification.verify')
        ->middleware('signed');

    Route::get('/volunteer/certificate/{event_id}/{attendee_id}', [PDFController::class, 'generateCertificate'])
        ->name('volunteer.certificate');
});


//Test Routes

