<?php

use App\Http\Controllers\QrController;
use App\Livewire\VolunteerRegistration;
use Illuminate\Support\Facades\Route;





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

Route::get('volunteer-registration', VolunteerRegistration::class);

Route::get('/qr/{event_id}/{attendee_id}', [QrController::class, 'scan_qr'])->name('qr.scan');
