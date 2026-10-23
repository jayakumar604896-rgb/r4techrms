<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RecruiterController;
use App\Http\Controllers\OfferController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TrackingUserStatusController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DemandController;
use App\Http\Controllers\DailyProgressController;
use App\Http\Controllers\BrochureController;
use App\Http\Controllers\SendtoOthermailController;
use App\Http\Controllers\SendtoCandidatemailController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\ServiceProviderDetailController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceReportController;
use App\Http\Controllers\DomesticProformanceInvoiceReportController;
use App\Http\Controllers\TaxInvoiceReportController;
use App\Http\Controllers\TaxInternationalInvoiceReportController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\CandidateFileController;

Route::get('/global-search', [CandidateController::class, 'globalSearch'])->name('global.search');
Route::get('/run-migration', function () {
    // Run the migrations
    Artisan::call('migrate', [
        '--force' => true,
    ]);

    return "Migrations run successfully: <br>" . nl2br(Artisan::output());
});
Route::get('/create-storage-link', function() {
    \Artisan::call('storage:link');
    return 'Storage link created: ' . \Artisan::output();
});

Route::get('/setup-database', function () {
    $output = [];
    
    // 1. Run migrations
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output[] = "<strong>✓ Migrations completed successfully</strong>";
    } catch (\Exception $e) {
        return "<h3>Setup Failed!</h3>Migration error: " . $e->getMessage();
    }
    
    // 2. Run specific seeders (Sector, Industry, PlacementType)
    $seeders = ['SectorSeeder', 'IndustrySeeder', 'PlacementTypeSeeder'];
    
    foreach ($seeders as $seeder) {
        try {
            Artisan::call('db:seed', ['--class' => $seeder]);
            $output[] = "✓ $seeder completed";
        } catch (\Exception $e) {
            $output[] = "✗ $seeder failed: " . $e->getMessage();
        }
    }
    
    return "<h3>Database Setup Complete!</h3>" . implode("<br>", $output);
})->middleware('auth');

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
Route::post('/upload-temp-pdf', [CandidateController::class, 'upload'])->name('temp.pdf.upload');
Route::get('/get-recipients-by-type', [CandidateController::class, 'getRecipientsByType'])->name('get.recipients.by.type');
Route::post('/login-user', [LoginController::class, 'login'])->name('login-submit');

Route::get('/otp-form', [LoginController::class, 'otpForm'])->name('otp-form');

Route::post('/otp-verify', [LoginController::class, 'verifyOtp'])->name('otp-verify');
Route::post('/tinymce-image_upload-front', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);
    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid file format or size exceeded.'], 400);
    }
    $path = $request->file('file')->store('uploads', 'public');
    return response()->json(['location' => asset('storage/' . $path)]);
})->name('tinymce.image_upload.front');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');
	Route::get('/dashboard-menu', [LoginController::class, 'menu'])->name('dashboard-menu');
    Route::get('/recruiter-dashboard', [LoginController::class, 'recruiterDashboard'])->name('recruiter-dashboard');
    Route::get('/recruiters', [RecruiterController::class, 'index'])->name('recruiters');
    Route::get('/add-user', [RecruiterController::class, 'addUser'])->name('add-user');
    Route::post('/recruiters/store', [RecruiterController::class, 'store'])->name('recruiters.store');
    Route::get('/edit/{id}', [RecruiterController::class, 'edit'])->name('recruiters.edit');
    Route::put('/update/{id}', [RecruiterController::class, 'update'])->name('recruiters.update');
    Route::get('/delete/{id}', [RecruiterController::class, 'destroy'])->name('recruiters.delete');
    Route::get('/view/{id}', [RecruiterController::class, 'show'])->name('recruiter.show');
    Route::post('/recruiters/update-status', [RecruiterController::class, 'updateStatus'])->name('recruiters.update.status');
    Route::get('/recruiter/daily-progress', [RecruiterController::class, 'dailyProgress'])->name('recruiter.daily');
    // In your web.php
    Route::get('/recruiters/all-daily', [RecruiterController::class, 'dailyProgressAll'])->name('admin.recruiters.all-daily');
    Route::get('/recruiter/view-details', [RecruiterController::class, 'viewRecruiterDetails'])->name('recruiter.daily.view');
    Route::post('/recruiter/daily-progress/save', [RecruiterController::class, 'saveDailyProgress'])->name('recruiter.daily.save');
    Route::get('/recruiter/daily-progress/{month}/data', [RecruiterController::class, 'getSavedData'])->name('recruiter.daily.data');
    
    // Monthly Progress
    Route::get('/recruiter/monthly-progress', [RecruiterController::class, 'monthlyProgress'])->name('recruiter.monthly');
    Route::get('/recruiter/monthly-progress/{month}', [RecruiterController::class, 'getMonthlyData'])->name('recruiter.monthly.data');
    Route::post('/recruiter/monthly-progress/calculate', [RecruiterController::class, 'calculateMonthly'])->name('recruiter.monthly.calculate');
    
    // Monthwise Progress
    Route::get('/recruiter/monthwise-progress', [RecruiterController::class, 'monthwiseProgress'])->name('recruiter.monthwise');
    Route::get('/recruiter/monthwise-progress/{year}', [RecruiterController::class, 'getYearlyData'])->name('recruiter.monthwise.data');
    Route::post('/recruiter/monthwise-progress/compare', [RecruiterController::class, 'compareMonths'])->name('recruiter.monthwise.compare');
    
    // Export Routes
    Route::get('/export/daily/{month}', [RecruiterController::class, 'exportDailyExcel'])->name('recruiter.export.daily');
    Route::get('/export/monthly/{month}', [RecruiterController::class, 'exportMonthlyExcel'])->name('recruiter.export.monthly');
    Route::get('/export/monthwise/{year}', [RecruiterController::class, 'exportMonthwiseExcel'])->name('recruiter.export.monthwise');
    Route::get('/candidates/create', [CandidateController::class, 'create'])->name('add-candidates');
    Route::get('/candidates/index', [CandidateController::class, 'index'])->name('candidates.index');
    Route::post('/candidates/store', [CandidateController::class, 'store'])->name('candidates.store');
    Route::get('/candidate-edit/{id}', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::put('/candidate-update/{id}', [CandidateController::class, 'update'])->name('candidates.update');
    Route::post('/candidates/send-pdf-email', [CandidateController::class, 'sendPdfEmail'])->name('candidates.send-pdf-email');
    Route::post('/recruiters/send-pdf-email', [RecruiterController::class, 'sendPdfEmail'])->name('recruiters.send-pdf-email');
    // Route::get('/delete/{id}', [CandidateController::class, 'destroy'])->name('candidates.delete');
    Route::get('/candidate-view/{id}', [CandidateController::class, 'show'])->name('candidates.show');
    Route::post('/candidates/send-to-recruiter', [CandidateController::class, 'sendToRecruiter'])->name('candidates.send-to-recruiter');
    Route::put('/candidates/{id}/update-status', [CandidateController::class, 'updateStatus'])->name('candidates.updateStatus');
    Route::get('/offer/index', [OfferController::class, 'index'])->name('offer.index');
    Route::get('/offer/create', [OfferController::class, 'create'])->name('offer.create');
    Route::post('/offer/store', [OfferController::class, 'store'])->name('offer.store');

    Route::get('/offer/view/{id}', [OfferController::class, 'show'])->name('offer.view');

    Route::get('/offer/edit/{id}', [OfferController::class, 'edit'])->name('offer.edit');
    Route::put('/offer/update/{id}', [OfferController::class, 'update'])->name('offer.update');

    Route::delete('/offer/delete/{id}', [OfferController::class, 'destroy'])->name('offer.delete');

    Route::resource('/demands', DemandController::class);
    Route::post('demands/{demand}/status', [DemandController::class, 'changeStatus'])->name('demands.status');
    Route::get('/demands/{id}/print', [DemandController::class, 'print'])->name('demands.print');

    Route::prefix('daily-progress')->name('daily-progress.')->group(function () {
        Route::get('/', [DailyProgressController::class, 'index'])->name('index');
        Route::get('/create', [DailyProgressController::class, 'create'])->name('create');
        Route::post('/', [DailyProgressController::class, 'store'])->name('store');
        Route::get('/{dailyProgress}/edit', [DailyProgressController::class, 'edit'])->name('edit');
        Route::put('/{dailyProgress}', [DailyProgressController::class, 'update'])->name('update');
        Route::delete('/{dailyProgress}', [DailyProgressController::class, 'destroy'])->name('destroy');
        
        // Import/Export
        Route::get('/import', [DailyProgressController::class, 'import'])->name('import');
        Route::post('/import', [DailyProgressController::class, 'processImport'])->name('process-import');
        Route::get('/export', [DailyProgressController::class, 'export'])->name('export');
    });

    Route::resource('newbrochures', BrochureController::class);
    Route::resource('sendtoothers', SendtoOthermailController::class);
    Route::post('/mailhistory/send-pdf-email', [SendtoOthermailController::class, 'sendPdfEmail'])->name('mailhistory.send-pdf-email');
    Route::resource('sendtocandidates', SendtoCandidatemailController::class);
    Route::resource('news', NewsController::class);
    Route::resource('company-settings', CompanySettingController::class);
    Route::resource('agents', AgentController::class);
    Route::get('agent/prints/{id}', [AgentController::class,'printClient']);
    Route::get('service-provider/prints/{id}', [ServiceProviderDetailController::class,'printProvider']);
    Route::resource('service-provider-details', ServiceProviderDetailController::class);
    Route::resource('clients', ClientController::class);
    Route::get('client/prints/{id}', [ClientController::class,'printClient']);
    Route::resource('invoice-reports', InvoiceReportController::class);
    Route::prefix('invoice-reports')->group(function () {
        Route::get('/statistics', [InvoiceReportController::class, 'statistics'])
            ->name('invoice-reports.statistics');
        
        Route::get('/export', [InvoiceReportController::class, 'export'])
            ->name('invoice-reports.export');
        
        Route::get('/{id}/print', [InvoiceReportController::class, 'print'])
            ->name('invoice-reports.print');
        
        Route::get('/search', [InvoiceReportController::class, 'searchForm'])
            ->name('invoice-reports.search.form');
        Route::post('/search', [InvoiceReportController::class, 'search'])
            ->name('invoice-reports.search');
        
        Route::post('/find-by-number', [InvoiceReportController::class, 'getByInvoiceNumber'])
            ->name('invoice-reports.find-by-number');
    });
    Route::resource('domestic-invoice-reports', DomesticProformanceInvoiceReportController::class);
    Route::prefix('domestic-invoice-reports')->group(function () {
        Route::get('/statistics', [InvoiceReportController::class, 'statistics'])
            ->name('domestic-invoice-reports.statistics');
        
        Route::get('/export', [InvoiceReportController::class, 'export'])
            ->name('domestic-invoice-reports.export');
        
        Route::get('/{id}/print', [InvoiceReportController::class, 'print'])
            ->name('domestic-invoice-reports.print');
        
        Route::get('/search', [InvoiceReportController::class, 'searchForm'])
            ->name('domestic-invoice-reports.search.form');
        Route::post('/search', [InvoiceReportController::class, 'search'])
            ->name('domestic-invoice-reports.search');
        
        Route::post('/find-by-number', [InvoiceReportController::class, 'getByInvoiceNumber'])
            ->name('domestic-invoice-reports.find-by-number');
    });
    Route::resource('tax-invoice-reports', TaxInvoiceReportController::class);
    Route::prefix('tax-invoice-reports')->group(function () {
        Route::get('/statistics', [InvoiceReportController::class, 'statistics'])
            ->name('tax-invoice-reports.statistics');
        
        Route::get('/export', [InvoiceReportController::class, 'export'])
            ->name('tax-invoice-reports.export');
        
        Route::get('/{id}/print', [InvoiceReportController::class, 'print'])
            ->name('tax-invoice-reports.print');
        
        Route::get('/search', [InvoiceReportController::class, 'searchForm'])
            ->name('tax-invoice-reports.search.form');
        Route::post('/search', [InvoiceReportController::class, 'search'])
            ->name('tax-invoice-reports.search');
        
        Route::post('/find-by-number', [InvoiceReportController::class, 'getByInvoiceNumber'])
            ->name('tax-invoice-reports.find-by-number');
    });
    Route::resource('tax-international-invoice-reports', TaxInternationalInvoiceReportController::class);
    Route::prefix('tax-international-invoice-reports')->group(function () {
        Route::get('/statistics', [TaxInternationalInvoiceReportController::class, 'statistics'])
            ->name('tax-international-invoice-reports.statistics');
        
        Route::get('/export', [TaxInternationalInvoiceReportController::class, 'export'])
            ->name('tax-international-invoice-reports.export');
        
        Route::get('/{id}/print', [TaxInternationalInvoiceReportController::class, 'print'])
            ->name('tax-international-invoice-reports.print');
        
        Route::get('/search', [TaxInternationalInvoiceReportController::class, 'searchForm'])
            ->name('tax-international-invoice-reports.search.form');
        Route::post('/search', [TaxInternationalInvoiceReportController::class, 'search'])
            ->name('tax-international-invoice-reports.search');
        
        Route::post('/find-by-number', [TaxInternationalInvoiceReportController::class, 'getByInvoiceNumber'])
            ->name('tax-international-invoice-reports.find-by-number');
    });
    Route::resource('receipts', ReceiptController::class);
    Route::resource('candidate-files', CandidateFileController::class);
    Route::post('/candidates/files', [CandidateController::class, 'getFiles'])->name('candidates.files');
	Route::post('/demands/generate-ref-no', [DemandController::class, 'generateReferenceNumber'])->name('demands.generate-ref-no');
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
