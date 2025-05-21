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
use App\Http\Controllers\VolunteerExportController;
use App\Notifications\VerifyEmailNotification;
use App\Settings\MailSettings;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
// use App\Http\Controllers\ChangePasswordController;

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

Route::get('/terms-and-conditions', function () {
    return view('terms-and-conditions');
})->name('terms-and-conditions');

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

    Route::post('/event/{event}/register-slot/{slot}', [EventRegistrationController::class, 'registerSlot'])
        ->name('event.register-slot');
    Route::delete('/event-registration/{registration}/cancel', [EventRegistrationController::class, 'cancelRegistration'])
        ->name('event.cancel-registration');

    Route::post('/events/{event}/bulletins', [EventBulletinController::class, 'store'])
        ->name('event.post-bulletin');
    Route::delete('/events/{event}/bulletins/{bulletin}', [EventBulletinController::class, 'destroy'])
        ->name('event.delete-bulletin');

    // Add this with your other event routes
    Route::get('/events/{event}/export-registrants', [EventRegistrationController::class, 'exportRegistrants'])
        ->name('event.export-registrants')
        ->middleware(['auth']);

    // Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password');
    // Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
});

// Add this with your other routes
Route::post('/volunteers/export', [VolunteerExportController::class, 'export'])
    ->name('volunteers.export')
    ->middleware(['auth']);

// OTP Verification Route
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/admin/auth/otp', App\Filament\Pages\Auth\OtpVerification::class)->name('filament.admin.auth.otp');
});

// Apply throttling middleware to login routes
Route::middleware(['throttle.login'])->group(function () {
    Route::match(['get', 'post'], '/admin/login', function () {
        return redirect()->route('filament.admin.auth.login');
    });
});

//Test Routes

Route::get('/test-verification-email/{uuid}', function ($uuid) {
    try {
        // Find user by uuid
        $user = User::where('id', $uuid)->first();

        if (!$user) {
            return "User with ID {$uuid} not found.";
        }

        // Load mail settings
        $settings = app(MailSettings::class);
        $settings->loadMailSettingsToConfig();

        // Send verification email
        $user->notify(new VerifyEmailNotification());

        return "Verification email sent to {$user->email} (ID: {$user->id}). Please check inbox and spam folder.";
    } catch (\Exception $e) {
        return "Error sending verification email: " . $e->getMessage();
    }
})->where('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');


Route::get('/admin/assign-volunteer-role', function () {
    // Only allow super admin or admin users
    if (!auth()->user() || !auth()->user()->hasAnyRole(['super_admin','Ayala Super Admin', 'admin'])) {
        abort(403, 'Unauthorized access');
    }

    // Find users that have volunteer=1 but no roles
    $usersWithoutRoles = User::where('volunteer', 1)
        ->whereDoesntHave('roles')
        ->get();

    $count = 0;

    // Get the Volunteer role
    $volunteerRole = Role::where('name', 'Volunteer')->first();

    if (!$volunteerRole) {
        return "Error: Volunteer role not found in database.";
    }

    DB::beginTransaction();
    try {
        // Assign the Volunteer role to each user
        foreach ($usersWithoutRoles as $user) {
            $user->assignRole($volunteerRole);
            $count++;

            Log::info("Volunteer role assigned to existing user", [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
        }

        DB::commit();
        return "Success: Assigned Volunteer role to {$count} users without roles.";
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Failed to assign volunteer roles", [
            'error' => $e->getMessage()
        ]);
        return "Error: Failed to assign roles. " . $e->getMessage();
    }
})->middleware('auth')->name('admin.assign-volunteer-role');

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
})->name('login');
