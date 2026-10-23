<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TrackingUserStatusController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CandidateController;
Route::get('/run-migration', function () {
    // Run the migrations
    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return "Migrations run successfully: <br>" . nl2br(Artisan::output());
});

Route::get('/schedule-list', function () {
    // Call the artisan command
    Artisan::call('schedule:list');

    // Get the output
    $output = Artisan::output();

    // Return formatted HTML output
    return "<pre>" . e($output) . "</pre>";
});

Route::get('/run-mark-idle', function () {
    Artisan::call('users:mark-idle');
    return "<pre>" . e(Artisan::output()) . "</pre>";
});

Route::post('/login-user', [LoginController::class, 'login'])->name('login-submit');

Route::get('/otp-form', [LoginController::class, 'otpForm'])->name('otp-form');

Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp-verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
    Route::get('/recruiter-dashboard', [LoginController::class, 'recruiterDashboard'])->name('recruiter-dashboard');
    Route::get('/recruiters', [RecruiterController::class, 'index'])->name('recruiters');
    Route::get('/add-user', [RecruiterController::class, 'addUser'])->name('add-user');
    Route::post('/recruiters/store', [RecruiterController::class, 'store'])->name('recruiters.store');
    Route::get('/edit/{id}', [RecruiterController::class, 'edit'])->name('recruiters.edit');
    Route::put('/update/{id}', [RecruiterController::class, 'update'])->name('recruiters.update');
    Route::get('/delete/{id}', [RecruiterController::class, 'destroy'])->name('recruiters.delete');
    Route::get('/view/{id}', [RecruiterController::class, 'show'])->name('recruiter.show');
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('add-candidates');
    Route::get('/candidates/index', [CandidateController::class, 'index'])->name('candidates.index');
    Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/candidate-edit/{id}', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::put('/candidate-update/{id}', [CandidateController::class, 'update'])->name('candidates.update');
    // Route::get('/delete/{id}', [CandidateController::class, 'destroy'])->name('candidates.delete');
    Route::get('/candidate-view/{id}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::post('/candidates/send-to-recruiter', [CandidateController::class, 'sendToRecruiter'])->name('candidates.send-to-recruiter');
    Route::get('/offer/index', [OfferController::class, 'index'])->name('offer.index');
Route::get('/offer/create', [OfferController::class, 'create'])->name('offer.create');
Route::post('/offer/store', [OfferController::class, 'store'])->name('offer.store');

Route::get('/offer/view/{id}', [OfferController::class, 'show'])->name('offer.view');

Route::get('/offer/edit/{id}', [OfferController::class, 'edit'])->name('offer.edit');
Route::put('/offer/update/{id}', [OfferController::class, 'update'])->name('offer.update');

Route::delete('/offer/delete/{id}', [OfferController::class, 'destroy'])->name('offer.delete');

});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('login');
})->name('login');

// routes/web.php
Route::post('/user/heartbeat', [TrackingUserStatusController::class, 'heartbeat'])
    ->middleware('auth');
Route::post('/user/status/{status}', [TrackingUserStatusController::class, 'status'])
->middleware('auth');
