<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidate;
use App\Models\News;
use App\Models\PlacementType;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\RecruiterDailyProgress;
use App\Models\Client;
use App\Models\Agent;
use App\Models\ServiceProviderDetail;
use App\Models\CommunicationLog;
use App\Models\Demand;
use App\Models\DomesticProformanceInvoiceReport;
use App\Models\TaxInvoiceReport;
use App\Models\TaxInternationalInvoiceReport;
use App\Models\InvoiceReport;
use App\Models\Receipt;
use App\Models\Offer;
use App\Models\CandidateFile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Validate request
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:100',
        ]);

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            // Check if user exists and password matches
            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            // Generate OTP (secure)
            $otp = random_int(100000, 999999);
           try {
                $updated = $user->update([
                    'otp' => $otp,
                    'otp_expires_at' => now()->addMinutes(10),
                ]);

                if (!$updated) {
                    throw new \Exception('Failed to update user OTP.');
                }

            } catch (\Throwable $e) {
                // Log the actual error message
                \Log::error('Error updating user OTP: ' . $e->getMessage());

                // Rethrow or handle gracefully
                throw $e;
            }

            // Send OTP via mail
            Mail::to($user->email)->queue(new OtpMail($otp, $user));

            // Temporarily store user ID in session
            session(['otp_user_id' => $user->id]);

            // Handle Remember Me (username only recommended)
            if ($request->has('remember')) {
                Cookie::queue('username', $request->email, 60 * 24 * 30); // 30 days
                Cookie::queue('password', $request->password, 60 * 24 * 30); // 30 days
                Cookie::queue('remember', true, 60 * 24 * 30);
            } else {
                Cookie::queue(Cookie::forget('username'));
                Cookie::queue(Cookie::forget('remember'));
                Cookie::queue(Cookie::forget('password'));
            }

            return redirect()->route('otp-form');

        } catch (\Exception $e) {
            // Handle any exception
            return back()->withErrors(['error' => 'Something went wrong. Please try again later.'])->withInput();
        }
    }
	public function menu()
    {
        $user = Auth::user();
        $userType = $user ? $user->user_type : null;
        $isScoped = in_array($userType, ['Domestic', 'International']);

        // 1. Resume Management counts
        $candQuery = Candidate::query();
        if ($isScoped) {
            $candQuery->where('user_type', $userType);
        }
        $totalCVs = (clone $candQuery)->count();
        $databaseCVs = (clone $candQuery)->whereNotNull('resume')->where('resume', '!=', '')->count();
        $websiteCVs = (clone $candQuery)->where(function($q) {
            $q->where('remarks', 'like', '%website%')->orWhere('code_no', 'like', '%web%');
        })->count();
        $otherCVs = (clone $candQuery)->where(function($q) {
            $q->whereNull('resume')->orWhere('resume', '');
        })->count();
        $shortlistedCVs = (clone $candQuery)->whereIn('status', ['Selected', 'Shortlisted'])->count();
        $submittedCVs = (clone $candQuery)->whereIn('status', ['Inprocess', 'Waitting List', 'Submitted', 'In Process'])->count();

        // 2. Daily Marketing counts
        $clientQuery = Client::query();
        if ($isScoped) {
            $clientQuery->where('user_type', $userType);
        }
        $panIndiaClientsCount = (clone $clientQuery)->where('user_type', 'Domestic')->count();
        $internationalClientsCount = (clone $clientQuery)->where('user_type', 'International')->count();

        $commQuery = CommunicationLog::where('type', 'Mail Send to Others');
        if ($isScoped) {
            $commQuery->where('user_type', $userType);
        }
        $panIndiaMarketingCount = (clone $commQuery)->where('user_type', 'Domestic')->count();
        $internationalMarketingCount = (clone $commQuery)->where('user_type', 'International')->count();
        $totalMarketingCount = (clone $commQuery)->count();
        if ($panIndiaMarketingCount === 0 && $internationalMarketingCount === 0 && $totalMarketingCount > 0) {
            $panIndiaMarketingCount = $totalMarketingCount;
        }

        // Marketing clients list
        $panIndiaMarketing = (clone $clientQuery)->where('user_type', 'Domestic')->limit(10)->get();
        $internationalMarketing = (clone $clientQuery)->where('user_type', 'International')->limit(10)->get();

        // 3. New Enrolment counts
        $employeesCount = User::where('role', 'recruiter')->count();
        $clientsCount = (clone $clientQuery)->count();

        $agentQuery = Agent::query();
        if ($isScoped) {
            $agentQuery->where('user_type', $userType);
        }
        $businessAgentsCount = (clone $agentQuery)->count();
        $subAgentsCount = (clone $agentQuery)->count();

        $spQuery = ServiceProviderDetail::query();
        if ($isScoped) {
            $spQuery->where('user_type', $userType);
        }
        $serviceProvidersCount = (clone $spQuery)->count();

        // 5. Demand Register counts
        $demandQuery = Demand::query();
        if ($isScoped) {
            $demandQuery->where('user_type', $userType);
        }
        $domesticDemands = (clone $demandQuery)->where('user_type', 'Domestic')->count();
        $internationalDemands = (clone $demandQuery)->where('user_type', 'International')->count();

        // 7. Invoice & Quotations counts
        $proformaInvoiceCount = ($isScoped && $userType === 'International') ? 0 : DomesticProformanceInvoiceReport::count();
        $taxInvoiceCount = ($isScoped && $userType === 'International') ? 0 : TaxInvoiceReport::count();
        $commercialInvoiceCount = ($isScoped && $userType === 'International') ? 0 : InvoiceReport::count();
        $saleInvoiceCount = ($isScoped && $userType === 'Domestic') ? 0 : TaxInternationalInvoiceReport::count();
        $quotationsCount = 0;
        $receiptsCount = Receipt::count();

        // 8. Mails Follow Up counts
        $clientMailsQuery = CommunicationLog::where('type', 'Mail Send to Others');
        if ($isScoped) {
            $clientMailsQuery->where('user_type', $userType);
        }
        $clientMails = $clientMailsQuery->count();

        $candMailsQuery = CommunicationLog::where('type', 'Mail Send to Candidate');
        if ($isScoped) {
            $candMailsQuery->where('user_type', $userType);
        }
        $candidateMails = $candMailsQuery->count();

        $offerReqQuery = Offer::query();
        if ($isScoped) {
            $offerReqQuery->where('user_type', $userType);
        }
        $offerRequests = $offerReqQuery->count();

        // 9. Offers to Candidate counts
        $preOfferPanIndia = ($isScoped && $userType === 'International') ? 0 : Offer::where('user_type', 'Domestic')->count();
        $mainOfferPanIndia = ($isScoped && $userType === 'International') ? 0 : Offer::where('user_type', 'Domestic')->count();
        $genEmailQuery = CommunicationLog::where('type', 'Mail Send to Candidate')->where('subject', 'like', '%offer%');
        if ($isScoped) {
            $genEmailQuery->where('user_type', $userType);
        }
        $generalEmailOffers = $genEmailQuery->count();
        $preOfferInternational = ($isScoped && $userType === 'Domestic') ? 0 : Offer::where('user_type', 'International')->count();
        $mainOfferInternational = ($isScoped && $userType === 'Domestic') ? 0 : Offer::where('user_type', 'International')->count();

		return view('menu', compact(
            'totalCVs',
            'databaseCVs',
            'websiteCVs',
            'otherCVs',
            'shortlistedCVs',
            'submittedCVs',
            'panIndiaClientsCount',
            'internationalClientsCount',
            'panIndiaMarketingCount',
            'internationalMarketingCount',
            'panIndiaMarketing',
            'internationalMarketing',
            'employeesCount',
            'clientsCount',
            'businessAgentsCount',
            'subAgentsCount',
            'serviceProvidersCount',
            'domesticDemands',
            'internationalDemands',
            'proformaInvoiceCount',
            'taxInvoiceCount',
            'commercialInvoiceCount',
            'saleInvoiceCount',
            'quotationsCount',
            'receiptsCount',
            'clientMails',
            'candidateMails',
            'offerRequests',
            'preOfferPanIndia',
            'mainOfferPanIndia',
            'generalEmailOffers',
            'preOfferInternational',
            'mainOfferInternational'
        ));
	}
    public function otpForm()
    {
        if (Auth::check()) {

            $user = Auth::user();
            if ($user->role === 'recruiter') {
                return redirect()->route('recruiter-dashboard');
            }
            return redirect()->route('dashboard');
        }
        $session = session('otp_user_id');
        if (!$session) {
            return redirect()->route('login')->withErrors('Session expired. Please login again.');
        }
        return view('otp-form');
    }

    public function dashboard() {
        $adminUsers         =   User::get(['id', 'name', 'user_status','role']);
        $userType           =   Auth::user()->user_type;
        // $query          =    Candidate::when(in_array($userType, ['Domestic', 'International']), fn($query) => $query->where('user_type', $userType))->orderByDesc('created_at');
        // $candidates     =    clone($query)->limit(10)->get();
        // $candidatecounts=    clone($query)->count();
        $query              =   Candidate::query()->when( in_array($userType, ['Domestic', 'International']), fn ($q) => $q->where('user_type', $userType) )->orderByDesc('created_at');
        $candidates = $query->orderByDesc('created_at')->get();
        $candidateCounts = [
            'total' => $candidates->where('user_type', 'Domestic')->count(),
            'inprogress' => $candidates->where('user_type', 'Domestic')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'Domestic')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'Domestic')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'Domestic')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'Domestic')->where('status', 'Back Out')->count(),
        ];
        $internationalcandidateCounts = [
            'total' => $candidates->where('user_type', 'International')->count(),
            'inprogress' => $candidates->where('user_type', 'International')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'International')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'International')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'International')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'International')->where('status', 'Back Out')->count(),
        ];
        $internationalgulfcandidateCounts = [
            'total' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->count(),
            'inprogress' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'International')->where('sub_division', 'GULF')->where('status', 'Back Out')->count(),
        ];
        $internationalseacandidateCounts = [
            'total' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->count(),
            'inprogress' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'International')->where('sub_division', 'SEA')->where('status', 'Back Out')->count(),
        ];
        $internationaleropecandidateCounts = [
            'total' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->count(),
            'inprogress' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'International')->where('sub_division', 'EUROPE')->where('status', 'Back Out')->count(),
        ];
        $internationalothercandidateCounts = [
            'total' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->count(),
            'inprogress' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type', 'International')->where('sub_division', 'OTHERS')->where('status', 'Back Out')->count(),
        ];
        // $internationalsubdivisionCounts = [
        //     'GULF' => $candidates->where('sub_division', 'GULF')->count(),
        //     'SEA' => $candidates->where('sub_division', 'SEA')->count(),
        //     'EUROPE' => $candidates->where('sub_division', 'EUROPE')->count(),
        //     'OTHERS' => $candidates->where('sub_division', 'OTHERS')->count(),
        // ];
        $news               =   News::where('is_active',1)->get();
        $interUsers         =   User::where('role', 'international')->get(['id', 'name', 'user_status']);
        $domesticUsers      =   User::where('role', 'domestic')->get(['id', 'name', 'user_status']);
        $placementtypes = PlacementType::pluck('name','id');
        $industries = Industry::pluck('name','id');
        $sectores = Sector::pluck('name','id');
        return view('dashboard', compact('adminUsers','interUsers','domesticUsers','candidates','candidateCounts','news','internationalcandidateCounts','placementtypes','industries','sectores','internationalothercandidateCounts','internationaleropecandidateCounts','internationalseacandidateCounts','internationalgulfcandidateCounts'));
    }

    public function recruiterDashboard() {
        $adminUsers = User::where('role', 'admin')->get(['id', 'name', 'user_status']);
        $interUsers = User::where('role', 'international')->get(['id', 'name', 'user_status']);
        $domesticUsers = User::where('role', 'domestic')->get(['id', 'name', 'user_status']);
        $user = Auth::user();
        $hrStaff = $user->name;
        
        // Get current month and previous month
        $currentMonth = Carbon::now()->format('F Y');
        $previousMonth = Carbon::now()->subMonth()->format('F Y');
        
        // Get data for current month
        $currentMonthData = RecruiterDailyProgress::where('month', $currentMonth)
            ->where('hr_staff', $hrStaff)
            ->get();
        
        // Get data for previous month
        $previousMonthData = RecruiterDailyProgress::where('month', $previousMonth)
            ->where('hr_staff', $hrStaff)
            ->get();
        
        // Calculate totals for current month
        $currentMonthTotals = [
            'busi_profile' => $currentMonthData->sum('busi_profile'),
            'tele_marketing' => $currentMonthData->sum('tele_marketing'),
            'client_empanelment' => $currentMonthData->sum('client_empanelment'),
            'cv_submitted' => $currentMonthData->sum('cv_submitted'),
            'interview' => $currentMonthData->sum('interview'),
            'interview_attended' => $currentMonthData->sum('interview_attended'),
            'rejected' => $currentMonthData->sum('rejected'),
            'selected' => $currentMonthData->sum('selected'),
            'job_reported' => $currentMonthData->sum('job_reported'),
            'invoiced' => $currentMonthData->sum('invoiced'),
            'payment_received' => $currentMonthData->sum('payment_received'),
        ];
        
        // Calculate totals for previous month
        $previousMonthTotals = [
            'busi_profile' => $previousMonthData->sum('busi_profile'),
            'tele_marketing' => $previousMonthData->sum('tele_marketing'),
            'client_empanelment' => $previousMonthData->sum('client_empanelment'),
            'cv_submitted' => $previousMonthData->sum('cv_submitted'),
            'interview' => $previousMonthData->sum('interview'),
            'interview_attended' => $previousMonthData->sum('interview_attended'),
            'rejected' => $previousMonthData->sum('rejected'),
            'selected' => $previousMonthData->sum('selected'),
            'job_reported' => $previousMonthData->sum('job_reported'),
            'invoiced' => $previousMonthData->sum('invoiced'),
            'payment_received' => $previousMonthData->sum('payment_received'),
        ];
        
        // Calculate selection and rejection percentages
        $currentSelectionPercent = $currentMonthTotals['interview_attended'] > 0 
            ? round(($currentMonthTotals['selected'] / $currentMonthTotals['interview_attended']) * 100, 1)
            : 0;
            
        $currentRejectionPercent = $currentMonthTotals['interview_attended'] > 0 
            ? round(($currentMonthTotals['rejected'] / $currentMonthTotals['interview_attended']) * 100, 1)
            : 0;
            
        $previousSelectionPercent = $previousMonthTotals['interview_attended'] > 0 
            ? round(($previousMonthTotals['selected'] / $previousMonthTotals['interview_attended']) * 100, 1)
            : 0;
            
        $previousRejectionPercent = $previousMonthTotals['interview_attended'] > 0 
            ? round(($previousMonthTotals['rejected'] / $previousMonthTotals['interview_attended']) * 100, 1)
            : 0;
        
        // Calculate overall progress for current month
        $currentMonthProgress = $this->calculateMonthlyProgress($currentMonthTotals);
        $previousMonthProgress = $this->calculateMonthlyProgress($previousMonthTotals);
        $userType           =   Auth::user()->user_type;

        $query              =   Candidate::query()->when( in_array($userType, ['Domestic', 'International']), fn ($q) => $q->where('user_type', $userType) )->orderByDesc('created_at');

        $candidates         =   (clone $query)->limit(10)->get();
        $candidatecounts    =   (clone $query)->count();
        $news               =   News::where('is_active',1)->get();
        return view('recruiter-dashboard', compact(
            'adminUsers',
            'interUsers',
            'domesticUsers',
            'currentMonth',
            'previousMonth',
            'currentMonthTotals',
            'previousMonthTotals',
            'currentSelectionPercent',
            'currentRejectionPercent',
            'previousSelectionPercent',
            'previousRejectionPercent',
            'currentMonthProgress',
            'previousMonthProgress',
            'hrStaff',
            'candidates',
            'candidatecounts',
            'news'
        ));
    }

    private function calculateMonthlyProgress($totals)
    {
        // Define targets (adjust these as needed)
        $targets = [
            'busi_profile' => 520,
            'tele_marketing' => 260,
            'cv_submitted' => 182,
            'selected' => 45.5,
            'job_reported' => 6,
            'payment_received' => 3
        ];
        
        $progress = [];
        
        foreach ($targets as $key => $target) {
            if ($target > 0) {
                $achieved = $totals[$key] ?? 0;
                $progress[$key] = min(100, round(($achieved / $target) * 100, 1));
            } else {
                $progress[$key] = 0;
            }
        }
        
        // Overall progress (weighted average)
        $weights = [
            'busi_profile' => 0.02,
            'tele_marketing' => 0.04,
            'cv_submitted' => 0.15,
            'selected' => 0.25,
            'job_reported' => 0.15,
            'payment_received' => 0.06
        ];
        
        $overall = 0;
        foreach ($weights as $key => $weight) {
            if (isset($progress[$key])) {
                $overall += ($progress[$key] / 100) * $weight * 100;
            }
        }
        
        // Add overall to progress array
        $progress['overall'] = round($overall, 1);
        
        return $progress;
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors('Session expired. Please login again.');
        }

        if ($user->otp === $request->otp && Carbon::now()->lte($user->otp_expires_at)) {
            // OTP correct and valid
            $user->update(['otp' => null, 'otp_expires_at' => null,'last_activity' => now(),
                'user_status' => 'active']);

            Auth::login($user);
            session()->forget('otp_user_id');
            // var_dump($user->role);
            // exit;
            if ($user->role == 'recruiter') {
                return redirect()->route('recruiter-dashboard');
            } else {
                return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function logout()
    {
        // Logout the user
         if (Auth::check()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->update([
                'user_status' => 'offline',
            ]);
        }
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }
}
