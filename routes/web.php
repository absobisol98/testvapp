<?php

use App\Livewire\VolunteerRegistration;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerRegistrationController;





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

Route::get('/', function () {
    return view('custom.main-landing');
})->name('home');

// Route::get('volunteer-registration', VolunteerRegistration::class);





Route::get('/volunteer-registration', [VolunteerRegistrationController::class, 'create'])->name('volunteer.form.create');
Route::post('/volunteer-registration', [VolunteerRegistrationController::class, 'store'])->name('volunteer.form.store');
