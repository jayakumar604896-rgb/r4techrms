<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use App\Models\RecruiterDailyProgress;
use App\Models\RecruiterMonthlySummary;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class RecruiterController extends Controller
{

    public function indexOldOld(Request $request)
    {
        $status = $request->get('status', 'all');
        $query = User::where('role', 'recruiter')->orderBy('date_of_joining','DESC'); // or whatever your user type is
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $users = $query->paginate(10);
        
        // Get counts for each status
        $counts = [
            'all' => User::where('role', 'recruiter')->count(),
            'Active' => User::where('role', 'recruiter')->where('status', 'Active')->count(),
            'Inactive' => User::where('role', 'recruiter')->where('status', 'Inactive')->count(),
            'Disable' => User::where('role', 'recruiter')->where('status', 'Disable')->count(),
        ];
        return view('recruiter/index', compact('users', 'counts'));
    }
	public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        
        $query = User::where('role', 'recruiter')->orderBy('date_of_joining', 'DESC');
        
        // Apply status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply type filter (International/Domestic)
        if ($type !== 'all') {
            $query->where('user_type', ucfirst($type));
        }
        
        $users = $query->paginate(10);
        
        // Get counts for statistics
        $counts = [
            'all' => User::where('role', 'recruiter')->count(),
            'Active' => User::where('role', 'recruiter')->where('status', 'Active')->count(),
            'Inactive' => User::where('role', 'recruiter')->where('status', 'Inactive')->count(),
            'Disable' => User::where('role', 'recruiter')->where('status', 'Disable')->count(),
            'International' => User::where('role', 'recruiter')->where('user_type', 'International')->count(),
            'Domestic' => User::where('role', 'recruiter')->where('user_type', 'Domestic')->count(),
        ];
        
        return view('recruiter/index', compact('users', 'counts'));
    }
    public function indexOld() {
        $users = User::where('role', 'recruiter')->orderBy('date_of_joining','ASC')->get();
        return view('recruiter/index', compact('users'));
    }

    public function addUser() {
        $users = User::where('role', 'recruiter')->get();
        return view('recruiter/add-user', compact('users'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|unique:users',
            'company_serial_number' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'staff_contact_no' => 'nullable|string|max:20',
            'user_type' => 'required|string', // Corrected
            'alternate_contact_no' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'aadhar_card_number' => 'nullable|string|max:20',
            'date_of_joining' => 'nullable|date',
            'dob' => 'nullable|date',
            'work_mode' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/'), $filename);
            $photoPath = 'uploads/'.$filename;
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Default password
            'role' => 'recruiter',
            'user_type' => $request->user_type, // This will now work
            'user_status' => 'active',
            'company_serial_number' => $request->company_serial_number,
            'staff_contact_no' => $request->staff_contact_no,
            'designation' => $request->designation,
            'alternate_contact_no' => $request->alternate_contact_no,
            'address' => $request->address,
            'aadhar_card_number' => $request->aadhar_card_number,
            'date_of_joining' => $request->date_of_joining,
            'work_mode' => $request->work_mode,
            'dob' => $request->dob,
            'photo' => $photoPath,
            'sub_division' => $request->user_type === 'Domestic' ? 'Domestic' : ($request->sub_division ?? null),
            'city'=>$request->city ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('recruiters')->with('success', 'User added successfully!');
    }


      // Show edit form
    public function edit($id) {
        $user = User::findOrFail($id);
        return view('recruiter.edit-user', compact('user'));
    }

    // Update recruiter
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validate request
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'email|unique:users,email,'.$user->id,
        'company_serial_number' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'staff_contact_no' => 'nullable|string|max:20',
        'alternate_contact_no' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'aadhar_card_number' => 'nullable|string|max:20',
        'date_of_joining' => 'nullable|date',
        'dob' => 'nullable|date',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'user_type' => 'required|string',
    ]);

    // Include user_type in the data to update
    $data = $request->only([
        'name', 'email', 'company_serial_number', 'designation',
        'staff_contact_no', 'alternate_contact_no', 'address',
        'aadhar_card_number', 'date_of_joining', 'dob', 'user_type','city','work_mode'
    ]);

    // Photo upload
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->move(public_path('uploads/'), $filename);
        $data['photo'] = 'uploads/'.$filename;

        // Delete old photo
        if ($user->photo && file_exists(public_path($user->photo))) {
            @unlink(public_path($user->photo));
        }
    } else {
        $data['photo'] = $user->photo;
    }
    $data['sub_division'] = $request->user_type === 'Domestic' ? 'Domestic' : ($request->sub_division ?? null);


    // Update user
    $user->update($data);

    return redirect()->route('recruiters')->with('success', 'Recruiter updated successfully!');
}




    // Delete recruiter
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('recruiters')->with('success', 'Recruiter deleted successfully!');
    }

   public function show($id) 
    {
        $user = User::findOrFail($id);
        return view('recruiter.view-user', compact('user')); // single user
    }
    public function dailyProgressOld()
    {
        $currentMonth = Carbon::now()->format('F Y');
        $currentDate = Carbon::now()->format('d.m.Y');
        
        // Check if we have saved data for this month
        $savedData = RecruiterDailyProgress::where('month', $currentMonth)
            ->where('hr_staff', 'Ms. Padmavathi')
            ->get();
        
        return view('recruiter.daily', compact('currentMonth', 'currentDate', 'savedData'));
    }
    
    public function saveDailyProgressOld(Request $request)
    {
        try {
            $data = json_decode($request->data, true);
            $month = $request->month;
            $hrStaff = 'Ms. Padmavathi';
            
            // Delete existing entries for this month
            RecruiterDailyProgress::where('month', $month)
                ->where('hr_staff', $hrStaff)
                ->delete();
            
            // Save each daily entry
            foreach ($data['entries'] as $entry) {
                RecruiterDailyProgress::create([
                    'month' => $month,
                    'hr_staff' => $hrStaff,
                    'entry_day' => $entry['day'],
                    'client_name' => $entry['client'] ?? null,
                    'busi_profile' => $entry['busi'] ?? 0,
                    'tele_marketing' => $entry['tele'] ?? 0,
                    'client_empanelment' => $entry['empanel'] ?? 0,
                    'cv_submitted' => $entry['cv'] ?? 0,
                    'interview_attended' => $entry['interview'] ?? 0,
                    'rejected' => $entry['rejected'] ?? 0,
                    'selected' => $entry['selected'] ?? 0,
                    'job_reported' => $entry['reported'] ?? 0,
                    'invoiced' => $entry['invoiced'] ?? 0,
                    'payment_received' => $entry['payment'] ?? 0,
                    'remarks' => $entry['remarks'] ?? null,
                    'entry_date' => Carbon::now(),
                ]);
            }
            
            // Calculate and save monthly summary
            $this->saveMonthlySummary($data, $month, $hrStaff);
            
            return back()->with('success', 'Data saved successfully!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error saving data: ' . $e->getMessage());
        }
    }
    
    private function saveMonthlySummaryOld($data, $month, $hrStaff)
    {
        // Calculate totals from entries
        $totals = [
            'busi_profile' => 0,
            'tele_marketing' => 0,
            'client_empanelment' => 0,
            'cv_submitted' => 0,
            'interview_attended' => 0,
            'rejected' => 0,
            'selected' => 0,
            'job_reported' => 0,
            'invoiced' => 0,
            'payment_received' => 0,
        ];
        
        foreach ($data['entries'] as $entry) {
            foreach ($totals as $key => $value) {
                $field = str_replace('_', '', $key);
                if (isset($entry[$field])) {
                    $totals[$key] += floatval($entry[$field]);
                }
            }
        }
        
        // Calculate percentages
        $targets = [
            'busi_profile' => 520,
            'tele_marketing' => 260,
            'client_empanelment' => 10.4,
            'cv_submitted' => 182,
            'interview_attended' => 45.5,
            'selected' => 45.5,
            'job_reported' => 6,
            'invoiced' => 6,
            'payment_received' => 3
        ];
        
        $unitProgress = [];
        $overallProgress = [];
        $overallTotal = 0;
        
        $wtgPercent = [
            'busi_profile' => 0.02,
            'tele_marketing' => 0.04,
            'client_empanelment' => 0.06,
            'cv_submitted' => 0.15,
            'interview_attended' => 0.15,
            'rejected' => 0,
            'selected' => 0.25,
            'job_reported' => 0.15,
            'invoiced' => 0.12,
            'payment_received' => 0.06
        ];
        
        foreach ($totals as $key => $achieved) {
            $target = $targets[$key] ?? 1;
            $unitProgress[$key] = $target > 0 ? ($achieved / $target * 100) : 0;
            
            $wtg = $wtgPercent[$key] ?? 0;
            $overallProgress[$key] = ($unitProgress[$key] / 100) * $wtg * 100;
            
            if (!is_nan($overallProgress[$key])) {
                $overallTotal += $overallProgress[$key];
            }
        }
        
        // Save to monthly summary
        RecruiterMonthlySummary::updateOrCreate(
            [
                'month' => $month,
                'hr_staff' => $hrStaff
            ],
            [
                'totals' => json_encode($totals),
                'unit_progress' => json_encode($unitProgress),
                'overall_progress' => json_encode($overallProgress),
                'overall_total' => $overallTotal,
                'updated_at' => Carbon::now()
            ]
        );
    }
    
    public function getSavedDataOld($month)
    {
        $data = RecruiterDailyProgress::where('month', $month)
            ->where('hr_staff', 'Ms. Padmavathi')
            ->get();
            
        return response()->json($data);
    }
    public function dailyProgress2Old(Request $request)
    {
        $user   =   Auth::user();
        $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        $currentDate = Carbon::now()->format('d.m.Y');
        $currentMonth = Carbon::now()->format('F Y');

        $hrStaff = $user->name;
        // Get months available for selection
        $availableMonths = RecruiterDailyProgress::select('month')
            ->where('hr_staff', $hrStaff)
            ->distinct()
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
            ->pluck('month')
            ->toArray();
        
        // Add current month if not exists
        if (!in_array($selectedMonth, $availableMonths)) {
            array_unshift($availableMonths, $selectedMonth);
        }
        
        // Get saved data for selected month
        $savedData = RecruiterDailyProgress::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get()
            ->keyBy('entry_day');
        
        // Calculate day of week for each day
        $monthYear = Carbon::createFromFormat('F Y', $selectedMonth);
        $daysInMonth = $monthYear->daysInMonth;
        
        return view('recruiter.daily', compact(
            'currentMonth', 
            'currentDate', 
            'savedData',
            'availableMonths',
            'selectedMonth',
            'daysInMonth',
            'hrStaff'
        ));
    }
    public function dailyProgress(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        $currentDate = Carbon::now()->format('d.m.Y');
        $currentMonth = Carbon::now()->format('F Y');
        $currentDay = Carbon::now()->format('j'); // Day of month without leading zeros (1-31)
        
        $hrStaff = $user->name;
        
        // Get months available for selection
        $availableMonths = RecruiterDailyProgress::select('month')
            ->where('hr_staff', $hrStaff)
            ->distinct()
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
            ->pluck('month')
            ->toArray();
        
        // Add current month if not exists
        if (!in_array($selectedMonth, $availableMonths)) {
            array_unshift($availableMonths, $selectedMonth);
        }
        
        // Get saved data for selected month
        $savedData = RecruiterDailyProgress::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get()
            ->keyBy('entry_day');
        
        // Calculate day of week for each day
        $monthYear = Carbon::createFromFormat('F Y', $selectedMonth);
        $daysInMonth = $monthYear->daysInMonth;
        
        // Check if selected month is current month
        $isCurrentMonth = ($selectedMonth == $currentMonth);
        
        return view('recruiter.daily', compact(
            'currentMonth', 
            'currentDate', 
            'currentDay',
            'savedData',
            'availableMonths',
            'selectedMonth',
            'daysInMonth',
            'hrStaff',
            'isCurrentMonth'
        ));
    }

    public function dailyProgressAll(Request $request)
    {
        $user = Auth::user();
        
        if($user->role === "admin") {
            // Admin can see all recruiters' data
            // Get all unique months from all recruiters
            $availableMonths = RecruiterDailyProgress::select('month')
                ->distinct()
                ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
                ->pluck('month')
                ->toArray();
            
            // Get all recruiters - handle empty results
            $allRecruiters = RecruiterDailyProgress::select('hr_staff')
                ->distinct()
                ->orderBy('hr_staff')
                ->pluck('hr_staff')
                ->toArray();
            
            // If no recruiters in daily progress, check monthly summary
            if (empty($allRecruiters)) {
                $allRecruiters = RecruiterMonthlySummary::select('hr_staff')
                    ->distinct()
                    ->orderBy('hr_staff')
                    ->pluck('hr_staff')
                    ->toArray();
            }
            
            // If still empty, set to empty array
            if (empty($allRecruiters)) {
                $allRecruiters = [];
            }
        }
        else{
            $hrStaff = $user->name;
            $availableMonths = RecruiterDailyProgress::select('month')
                ->where('hr_staff', $hrStaff)
                ->distinct()
                ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
                ->pluck('month')
                ->toArray();
                $allRecruiters = RecruiterDailyProgress::select('hr_staff')
                ->where('hr_staff', $hrStaff)
                ->distinct()
                ->orderBy('hr_staff')
                ->pluck('hr_staff')
                ->toArray();
            
            // If no recruiters in daily progress, check monthly summary
            if (empty($allRecruiters)) {
                $allRecruiters = RecruiterMonthlySummary::select('hr_staff')
                    ->distinct()
                    ->orderBy('hr_staff')
                    ->pluck('hr_staff')
                    ->toArray();
            }
            
            // If still empty, set to empty array
            if (empty($allRecruiters)) {
                $allRecruiters = [];
            }
        }
            // Get selected month (default to current month)
            $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
            
            // Get data for all recruiters for selected month
            $allData = [];
            $recruiterSummaries = [];
            
            foreach ($allRecruiters as $recruiter) {
                // Get daily data
                $data = RecruiterDailyProgress::where('month', $selectedMonth)
                    ->where('hr_staff', $recruiter)
                    ->orderBy('entry_day')
                    ->get();
                
                if ($data->isNotEmpty()) {
                    $allData[$recruiter] = $data;
                }
                
                // Get monthly summary
                $summary = RecruiterMonthlySummary::where('month', $selectedMonth)
                    ->where('hr_staff', $recruiter)
                    ->first();
                
                if ($summary) {
                    $recruiterSummaries[$recruiter] = [
                        'overall_progress' => $summary->overall_total,
                        'totals' => json_decode($summary->totals, true),
                        'unit_progress' => json_decode($summary->unit_progress, true),
                        'performance_rating' => $this->getPerformanceRating($summary->overall_total)
                    ];
                } else {
                    // Create empty summary for recruiters without data
                    $recruiterSummaries[$recruiter] = [
                        'overall_progress' => 0,
                        'totals' => [
                            'busi_profile' => 0,
                            'tele_marketing' => 0,
                            'client_empanelment' => 0,
                            'cv_submitted' => 0,
                            'interview_attended' => 0,
                            'rejected' => 0,
                            'selected' => 0,
                            'job_reported' => 0,
                            'invoiced' => 0,
                            'payment_received' => 0
                        ],
                        'unit_progress' => [],
                        'performance_rating' => ['label' => 'No Data', 'class' => 'bg-secondary']
                    ];
                }
            }
            
            // Calculate total CVs for all recruiters
            $totalCVs = 0;
            $totalSelections = 0;
            $totalProgress = 0;
            $activeCount = 0;
            
            foreach ($recruiterSummaries as $summary) {
                $totalCVs += $summary['totals']['cv_submitted'] ?? 0;
                $totalSelections += $summary['totals']['selected'] ?? 0;
                $totalProgress += $summary['overall_progress'];
                
                if ($summary['overall_progress'] > 0) {
                    $activeCount++;
                }
            }
            
            // Calculate averages
            $avgProgress = count($allRecruiters) > 0 ? round($totalProgress / count($allRecruiters), 1) : 0;
            $conversionRate = $totalCVs > 0 ? round(($totalSelections / $totalCVs) * 100, 1) : 0;
            
            return view('recruiter.dailyreport', compact(
                'availableMonths',
                'allRecruiters',
                'selectedMonth',
                'allData',
                'recruiterSummaries',
                'user',
                'totalCVs',
                'totalSelections',
                'avgProgress',
                'conversionRate',
                'activeCount'
            ));
            
        // } else {
        //     // Regular recruiter sees only their data
        //     $hrStaff = $user->name;
        //      $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        //     $currentDate = Carbon::now()->format('d.m.Y');
        //     $currentMonth = Carbon::now()->format('F Y');
        //     $availableMonths = RecruiterDailyProgress::select('month')
        //         ->where('hr_staff', $hrStaff)
        //         ->distinct()
        //         ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
        //         ->pluck('month')
        //         ->toArray();
            
        //     $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
            
        //     $savedData = RecruiterDailyProgress::where('month', $selectedMonth)
        //         ->where('hr_staff', $hrStaff)
        //         ->orderBy('entry_day')
        //         ->get()
        //         ->keyBy('entry_day');
            
        //     return view('recruiter.daily', compact(
        //         'savedData',
        //         'availableMonths',
        //         'currentMonth',
        //         'currentDate',
        //         'selectedMonth',
        //         'hrStaff',
        //         'user'
        //     ));
        // }
    }

    public static function getPerformanceRatingStatic($progress)
    {
        if ($progress >= 90) return ['label' => 'Excellent', 'class' => 'bg-success'];
        if ($progress >= 75) return ['label' => 'Good', 'class' => 'bg-primary'];
        if ($progress >= 60) return ['label' => 'Average', 'class' => 'bg-info'];
        if ($progress >= 40) return ['label' => 'Below Average', 'class' => 'bg-warning'];
        return ['label' => 'Poor', 'class' => 'bg-danger'];
    }

    // Update the instance method to use static method
    public function getPerformanceRating($progress)
    {
        return self::getPerformanceRatingStatic($progress);
    }
    public function saveDailyProgress160226(Request $request)
    {
        $user = Auth::user();
        $data = json_decode($request->input('data'), true);
        
        // Validate that we're only saving today's data
        $currentDay = Carbon::now()->format('j');
        $currentMonth = Carbon::now()->format('F Y');
        $currentDate = Carbon::now()->format('Y-m-d'); // For database format
        
        if($data['month'] != $currentMonth || $data['day'] != $currentDay) {
            return redirect()->back()->with('error', 'You can only save data for today!');
        }
        
        foreach($data['entries'] as $entry) {
            // Create the full date from month and day
            $monthYear = Carbon::createFromFormat('F Y', $data['month']);
            $entryDate = $monthYear->copy()->setDay($entry['day'])->format('Y-m-d');
            
            RecruiterDailyProgress::updateOrCreate(
                [
                    'month' => $data['month'],
                    'entry_day' => $entry['day'],
                    'hr_staff' => $user->name
                ],
                [
                    'entry_date' => $entryDate, // Add this line
                    'client_name' => $entry['client'],
                    'busi_profile' => $entry['busi'],
                    'tele_marketing' => $entry['tele'],
                    'client_empanelment' => $entry['empanel'],
                    'cv_submitted' => $entry['cv'],
                    'interview' => $entry['interview'],
                    'rejected' => $entry['rejected'],
                    'selected' => $entry['selected'],
                    'job_reported' => $entry['reported'],
                    'invoiced' => $entry['invoiced'],
                    'payment_received' => $entry['payment'],
                    'remarks' => $entry['remarks']
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Data saved successfully!');
    }
    public function saveDailyProgress(Request $request)
    {
        $user = Auth::user();
        $data = json_decode($request->input('data'), true);
        
        // Optional: prevent saving months that are too far in the future/past
        // For example, only allow the current month or the previous month.
        $requestedMonth = Carbon::createFromFormat('F Y', $data['month']);
        $now = Carbon::now();
        
        // Allow only current month or previous month (adjust as needed)
        if ($requestedMonth->diffInMonths($now) > 1) {
            return redirect()->back()->with('error', 'You can only save data for the current or previous month.');
        }
        
        // Save each entry
        foreach($data['entries'] as $entry) {
            // Build the full date from month and day
            $monthYear = Carbon::createFromFormat('F Y', $data['month']);
            $entryDate = $monthYear->copy()->day((int)$entry['day'])->format('Y-m-d');
            // $monthYear = Carbon::createFromFormat('F Y', $data['month']);
            // $entryDate = $monthYear->copy()->setDay($entry['day'])->format('Y-m-d');
            
            RecruiterDailyProgress::updateOrCreate(
                [
                    'month'     => $data['month'],
                    'entry_day' => $entry['day'],
                    'hr_staff'  => $user->name
                ],
                [
                    'entry_date'         => $entryDate,
                    'client_name'        => $entry['client'],
                    'busi_profile'       => $entry['busi'],
                    'tele_marketing'     => $entry['tele'],
                    'client_empanelment' => $entry['empanel'],
                    'cv_submitted'       => $entry['cv'],
                    'interview'          => $entry['interview'],
                    'rejected'           => $entry['rejected'],
                    'selected'           => $entry['selected'],
                    'job_reported'       => $entry['reported'],
                    'invoiced'           => $entry['invoiced'],
                    'payment_received'   => $entry['payment'],
                    'remarks'            => $entry['remarks']
                ]
            );
        }
        
        return redirect()->back()->with('success', 'All data saved successfully!');
    }
    public function getSavedData($month)
    {
        $user   =   Auth::user();

        $data = RecruiterDailyProgress::where('month', $month)
            ->where('hr_staff', $user->name)
            ->orderBy('entry_day')
            ->get();
            
        return response()->json($data);
    }
    
    private function saveMonthlySummary($data, $month, $hrStaff)
    {
        // Calculate totals from entries
        $totals = [
            'busi_profile' => 0,
            'tele_marketing' => 0,
            'client_empanelment' => 0,
            'cv_submitted' => 0,
            'interview_attended' => 0,
            'rejected' => 0,
            'selected' => 0,
            'job_reported' => 0,
            'invoiced' => 0,
            'payment_received' => 0,
        ];
        
        foreach ($data['entries'] as $entry) {
            $totals['busi_profile'] += floatval($entry['busi'] ?? 0);
            $totals['tele_marketing'] += floatval($entry['tele'] ?? 0);
            $totals['client_empanelment'] += floatval($entry['empanel'] ?? 0);
            $totals['cv_submitted'] += floatval($entry['cv'] ?? 0);
            $totals['interview_attended'] += floatval($entry['interview'] ?? 0);
            $totals['rejected'] += floatval($entry['rejected'] ?? 0);
            $totals['selected'] += floatval($entry['selected'] ?? 0);
            $totals['job_reported'] += floatval($entry['reported'] ?? 0);
            $totals['invoiced'] += floatval($entry['invoiced'] ?? 0);
            $totals['payment_received'] += floatval($entry['payment'] ?? 0);
        }
        
        // Calculate percentages
        $targets = [
            'busi_profile' => 520,
            'tele_marketing' => 260,
            'client_empanelment' => 10.4,
            'cv_submitted' => 182,
            'interview_attended' => 45.5,
            'selected' => 45.5,
            'job_reported' => 6,
            'invoiced' => 6,
            'payment_received' => 3
        ];
        
        $unitProgress = [];
        $overallProgress = [];
        $overallTotal = 0;
        
        $wtgPercent = [
            'busi_profile' => 0.02,
            'tele_marketing' => 0.04,
            'client_empanelment' => 0.06,
            'cv_submitted' => 0.15,
            'interview_attended' => 0.15,
            'rejected' => 0,
            'selected' => 0.25,
            'job_reported' => 0.15,
            'invoiced' => 0.12,
            'payment_received' => 0.06
        ];
        
        foreach ($totals as $key => $achieved) {
            $target = $targets[$key] ?? 1;
            $unitProgress[$key] = $target > 0 ? ($achieved / $target * 100) : 0;
            
            $wtg = $wtgPercent[$key] ?? 0;
            $overallProgress[$key] = ($unitProgress[$key] / 100) * $wtg * 100;
            
            if (!is_nan($overallProgress[$key])) {
                $overallTotal += $overallProgress[$key];
            }
        }
        
        // Calculate additional metrics
        $daysWorked = count(array_filter($data['entries'], function($entry) {
            return ($entry['cv'] ?? 0) > 0 || ($entry['interview'] ?? 0) > 0 || ($entry['busi'] ?? 0) > 0;
        }));
        
        $selectionRate = $totals['interview_attended'] > 0 ? 
            ($totals['selected'] / $totals['interview_attended'] * 100) : 0;
        
        $rejectionRate = $totals['interview_attended'] > 0 ? 
            ($totals['rejected'] / $totals['interview_attended'] * 100) : 0;
        
        // Save to monthly summary
        RecruiterMonthlySummary::updateOrCreate(
            [
                'month' => $month,
                'hr_staff' => $hrStaff
            ],
            [
                'totals' => json_encode($totals),
                'unit_progress' => json_encode($unitProgress),
                'overall_progress' => json_encode($overallProgress),
                'overall_total' => $overallTotal,
                'days_worked' => $daysWorked,
                'selection_rate' => $selectionRate,
                'rejection_rate' => $rejectionRate,
                'cv_per_day' => $daysWorked > 0 ? $totals['cv_submitted'] / $daysWorked : 0,
                'updated_at' => Carbon::now()
            ]
        );
    }
    
    // ==================== MONTHLY PROGRESS ====================
    public function monthlyProgress(Request $request)
    {
        $user = Auth::user();
        $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        $currentMonth = Carbon::now()->format('F Y');
        
        // Check if admin is viewing specific recruiter
        if ($user->role === 'admin' && $request->has('hr_staff')) {
            $hrStaff = $request->hr_staff;
        } else {
            $hrStaff = $user->name;
        }
        
        // Get available months for dropdown
        $availableMonths = RecruiterMonthlySummary::select('month')
            ->when($user->role !== 'admin', function($query) use ($hrStaff) {
                return $query->where('hr_staff', $hrStaff);
            })
            ->distinct()
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
            ->pluck('month')
            ->toArray();
        
        // Get monthly summary for selected month
        $monthlySummary = RecruiterMonthlySummary::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->first();
        
        // Get daily data for charts
        $dailyData = RecruiterDailyProgress::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get();
        
        // Calculate weekly breakdown
        $weeklyBreakdown = $this->calculateWeeklyBreakdown($dailyData);
        
        // Calculate trends (compare with previous month)
        $trends = $this->calculateMonthlyTrends($selectedMonth, $hrStaff);
        
        // Get target data
        $targets = $this->getMonthlyTargets();
        
        // Get all recruiters for admin dropdown
        $allRecruiters = [];
        if ($user->role === 'admin') {
            $allRecruiters = RecruiterMonthlySummary::select('hr_staff')
                ->distinct()
                ->orderBy('hr_staff')
                ->pluck('hr_staff')
                ->toArray();
        }
        
        // Calculate dynamic KPI metrics
        $kpiMetrics = $this->calculateKPIMetrics($monthlySummary, $trends, $targets);
        
        // Prepare chart data
        $chartData = $this->prepareChartData($dailyData);
        
        return view('recruiter.monthly', compact(
            'selectedMonth',
            'currentMonth',
            'availableMonths',
            'monthlySummary',
            'dailyData',
            'weeklyBreakdown',
            'trends',
            'targets',
            'hrStaff',
            'allRecruiters',
            'user',
            'kpiMetrics',
            'chartData'
        ));
    }

    private function calculateKPIMetrics($monthlySummary, $trends, $targets)
    {
        if (!$monthlySummary) {
            return [
                'cvs' => ['value' => 0, 'target' => $targets['cv_submitted'], 'percentage' => 0, 'trend' => 0],
                'interviews' => ['value' => 0, 'target' => $targets['interview_attended'], 'percentage' => 0, 'trend' => 0],
                'selections' => ['value' => 0, 'target' => $targets['selected'], 'percentage' => 0, 'trend' => 0],
                'payments' => ['value' => 0, 'target' => $targets['payment_received'], 'percentage' => 0, 'trend' => 0],
            ];
        }
        
        $totals = json_decode($monthlySummary->totals, true);
        
        return [
            'cvs' => [
                'value' => $totals['cv_submitted'] ?? 0,
                'target' => $targets['cv_submitted'],
                'percentage' => $targets['cv_submitted'] > 0 ? 
                    round(($totals['cv_submitted'] ?? 0) / $targets['cv_submitted'] * 100, 1) : 0,
                'trend' => $trends['cv_submitted'] ?? 0
            ],
            'interviews' => [
                'value' => $totals['interview_attended'] ?? 0,
                'target' => $targets['interview_attended'],
                'percentage' => $targets['interview_attended'] > 0 ? 
                    round(($totals['interview_attended'] ?? 0) / $targets['interview_attended'] * 100, 1) : 0,
                'trend' => $trends['interview_attended'] ?? 0
            ],
            'selections' => [
                'value' => $totals['selected'] ?? 0,
                'target' => $targets['selected'],
                'percentage' => $targets['selected'] > 0 ? 
                    round(($totals['selected'] ?? 0) / $targets['selected'] * 100, 1) : 0,
                'trend' => $trends['selected'] ?? 0
            ],
            'payments' => [
                'value' => $totals['payment_received'] ?? 0,
                'target' => $targets['payment_received'],
                'percentage' => $targets['payment_received'] > 0 ? 
                    round(($totals['payment_received'] ?? 0) / $targets['payment_received'] * 100, 1) : 0,
                'trend' => $trends['payment_received'] ?? 0
            ],
        ];
    }

    private function prepareChartData($dailyData)
    {
        $cvData = [];
        $interviewData = [];
        $selectionData = [];
        
        for ($day = 1; $day <= 31; $day++) {
            $entry = $dailyData->where('entry_day', $day)->first();
            $cvData[] = $entry ? $entry->cv_submitted : 0;
            $interviewData[] = $entry ? $entry->interview_attended : 0;
            $selectionData[] = $entry ? $entry->selected : 0;
        }
        
        return [
            'cv_data' => $cvData,
            'interview_data' => $interviewData,
            'selection_data' => $selectionData
        ];
    }
    public function monthlyProgressOld(Request $request)
    {
        $user   =   Auth::user();
        $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        $hrStaff = $user->name;
        $currentMonth = Carbon::now()->format('F Y');

        
        // Get available months with data
        $availableMonths = RecruiterMonthlySummary::select('month')
            ->where('hr_staff', $hrStaff)
            ->distinct()
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
            ->pluck('month')
            ->toArray();
        
        // Get monthly summary for selected month
        $monthlySummary = RecruiterMonthlySummary::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->first();
        
        // Get daily data for charts
        $dailyData = RecruiterDailyProgress::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get();
        
        // Calculate weekly breakdown
        $weeklyBreakdown = $this->calculateWeeklyBreakdown($dailyData);
        
        // Calculate trends (compare with previous month)
        $trends = $this->calculateMonthlyTrends($selectedMonth, $hrStaff);
        
        // Get target data
        $targets = $this->getMonthlyTargets();
        
        return view('recruiter.monthly', compact(
            'selectedMonth',
            'currentMonth',
            'availableMonths',
            'monthlySummary',
            'dailyData',
            'weeklyBreakdown',
            'trends',
            'targets',
            'hrStaff'
        ));
    }
    
    public function getMonthlyData($month)
    {
        $user   =   Auth::user();
        $hrStaff = $user->name;
        
        $monthlySummary = RecruiterMonthlySummary::where('month', $month)
            ->where('hr_staff', $hrStaff)
            ->first();
            
        if (!$monthlySummary) {
            return response()->json(['error' => 'No data found for this month'], 404);
        }
        
        $dailyData = RecruiterDailyProgress::where('month', $month)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get();
        
        $weeklyBreakdown = $this->calculateWeeklyBreakdown($dailyData);
        
        return response()->json([
            'summary' => $monthlySummary,
            'daily_data' => $dailyData,
            'weekly_breakdown' => $weeklyBreakdown
        ]);
    }
    
    public function calculateMonthly(Request $request)
    {
        try {
            $user   =   Auth::user();
            $month = $request->month;
            $hrStaff = $user->name;
            
            // Get all daily entries for this month
            $dailyEntries = RecruiterDailyProgress::where('month', $month)
                ->where('hr_staff', $hrStaff)
                ->get();
            
            // Prepare data for monthly summary calculation
            $data = [
                'entries' => []
            ];
            
            for ($day = 1; $day <= 31; $day++) {
                $entry = $dailyEntries->where('entry_day', $day)->first();
                $data['entries'][] = [
                    'day' => $day,
                    'busi' => $entry->busi_profile ?? 0,
                    'tele' => $entry->tele_marketing ?? 0,
                    'empanel' => $entry->client_empanelment ?? 0,
                    'cv' => $entry->cv_submitted ?? 0,
                    'interview' => $entry->interview_attended ?? 0,
                    'rejected' => $entry->rejected ?? 0,
                    'selected' => $entry->selected ?? 0,
                    'reported' => $entry->job_reported ?? 0,
                    'invoiced' => $entry->invoiced ?? 0,
                    'payment' => $entry->payment_received ?? 0,
                ];
            }
            
            // Recalculate and save monthly summary
            $this->saveMonthlySummary($data, $month, $hrStaff);
            
            return response()->json([
                'success' => true,
                'message' => 'Monthly summary recalculated successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating monthly data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function calculateWeeklyBreakdown($dailyData)
    {
        $weeks = [
            'Week 1' => ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0],
            'Week 2' => ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0],
            'Week 3' => ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0],
            'Week 4' => ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0],
            'Week 5' => ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0],
        ];
        
        foreach ($dailyData as $entry) {
            $weekNumber = ceil($entry->entry_day / 7);
            $weekKey = 'Week ' . min($weekNumber, 5);
            
            $weeks[$weekKey]['cv'] += $entry->cv_submitted;
            $weeks[$weekKey]['interview'] += $entry->interview_attended;
            $weeks[$weekKey]['selected'] += $entry->selected;
            $weeks[$weekKey]['payment'] += $entry->payment_received;
        }
        
        return $weeks;
    }
    
    private function calculateMonthlyTrends($currentMonth, $hrStaff)
    {
        $current = Carbon::createFromFormat('F Y', $currentMonth);
        $previousMonth = $current->copy()->subMonth()->format('F Y');
        
        $currentData = RecruiterMonthlySummary::where('month', $currentMonth)
            ->where('hr_staff', $hrStaff)
            ->first();
            
        $previousData = RecruiterMonthlySummary::where('month', $previousMonth)
            ->where('hr_staff', $hrStaff)
            ->first();
        
        $trends = [];
        
        if ($currentData && $previousData) {
            $currentTotals = json_decode($currentData->totals, true);
            $previousTotals = json_decode($previousData->totals, true);
            
            foreach ($currentTotals as $key => $currentValue) {
                $previousValue = $previousTotals[$key] ?? 0;
                if ($previousValue > 0) {
                    $trends[$key] = (($currentValue - $previousValue) / $previousValue) * 100;
                } else {
                    $trends[$key] = $currentValue > 0 ? 100 : 0;
                }
            }
        }
        
        return $trends;
    }
    
    private function getMonthlyTargets()
    {
        return [
            'busi_profile' => 520,
            'tele_marketing' => 260,
            'client_empanelment' => 10.4,
            'cv_submitted' => 182,
            'interview_attended' => 45.5,
            'selected' => 45.5,
            'job_reported' => 6,
            'invoiced' => 6,
            'payment_received' => 3
        ];
    }
    
    // ==================== MONTHWISE PROGRESS ====================
    
    public function monthwiseProgress(Request $request)
    {
        $user = Auth::user();
        $selectedYear = $request->get('year', Carbon::now()->year);
        
        // Determine HR Staff based on role
        if ($user->role==='admin') {
            // Admin/Manager can view all data or select specific staff
            $hrStaff = $request->get('hr_staff', $user->name);
        } else {
            // Regular recruiter can only view their own data
            $hrStaff = $user->name;
        }
        
        // Get available years
        $availableYears = RecruiterMonthlySummary::selectRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) as year')
            ->when(!$user->role==='admin', function($query) use ($hrStaff) {
                return $query->where('hr_staff', $hrStaff);
            })
            ->distinct()
            ->orderBy('year', 'DESC')
            ->pluck('year')
            ->toArray();
        
        // Get all monthly data for selected year
        $monthlyData = RecruiterMonthlySummary::query()
            ->when(!$user->role==='admin', function($query) use ($hrStaff) {
                return $query->where('hr_staff', $hrStaff);
            })
            ->whereRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) = ?', [$selectedYear])
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y')")
            ->get();
        
        // Calculate quarter-wise data
        $quarterData = $this->calculateQuarterData($monthlyData);
        
        // Calculate YTD totals and averages
        $ytdData = $this->calculateYTDData($monthlyData);
        
        // Calculate trends and insights
        $insights = $this->calculateMonthwiseInsights($monthlyData);
        
        // Get comparison with previous year
        $previousYearData = $this->getPreviousYearComparison($selectedYear, $hrStaff);
        
        // Get all HR staff for admin dropdown
        $allHrStaff = [];
        if ($user->role==='admin') {
            $allHrStaff = RecruiterMonthlySummary::select('hr_staff')
                ->distinct()
                ->orderBy('hr_staff')
                ->pluck('hr_staff')
                ->toArray();
        }
        
        return view('recruiter.monthwise', compact(
            'selectedYear',
            'availableYears',
            'monthlyData',
            'quarterData',
            'ytdData',
            'insights',
            'previousYearData',
            'hrStaff',
            'allHrStaff',
            'user'
        ));
    }

    // Add this method to calculate dynamic months data
    private function prepareMonthsData($monthlyData, $selectedYear)
    {
        $months = [];
        $currentMonth = Carbon::now();
        $selectedYearDate = Carbon::createFromDate($selectedYear, 1, 1);
        
        for ($i = 1; $i <= 12; $i++) {
            $monthDate = Carbon::createFromDate($selectedYear, $i, 1);
            $monthName = $monthDate->format('M');
            $quarter = 'Q' . ceil($i / 3);
            
            // Find data for this month
            $monthRecord = $monthlyData->firstWhere(function($item) use ($monthName, $selectedYear) {
                $itemDate = Carbon::createFromFormat('F Y', $item->month);
                return $itemDate->format('M') === $monthName && 
                    $itemDate->year == $selectedYear;
            });
            
            // Determine status
            if ($monthDate->year < $currentMonth->year || 
                ($monthDate->year == $currentMonth->year && $monthDate->month < $currentMonth->month)) {
                $status = 'completed';
            } elseif ($monthDate->year == $currentMonth->year && $monthDate->month == $currentMonth->month) {
                $status = 'current';
            } else {
                $status = 'upcoming';
            }
            
            $totals = $monthRecord ? json_decode($monthRecord->totals, true) : [];
            
            $months[] = [
                'name' => $monthName,
                'full_name' => $monthDate->format('F Y'),
                'quarter' => $quarter,
                'cvs' => $totals['cv_submitted'] ?? 0,
                'selections' => $totals['selected'] ?? 0,
                'progress' => $monthRecord ? $monthRecord->overall_total : 0,
                'status' => $status,
                'has_data' => !is_null($monthRecord)
            ];
        }
        
        return $months;
    }
    public function monthwiseProgressOld(Request $request)
    {
        $user   =   Auth::user();
        $selectedYear = $request->get('year', Carbon::now()->year);
        $hrStaff = $user->name;
        
        // Get available years
        $availableYears = RecruiterMonthlySummary::selectRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) as year')
            ->where('hr_staff', $hrStaff)
            ->distinct()
            ->orderBy('year', 'DESC')
            ->pluck('year')
            ->toArray();
        
        // Get all monthly data for selected year
        $monthlyData = RecruiterMonthlySummary::where('hr_staff', $hrStaff)
            ->whereRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) = ?', [$selectedYear])
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y')")
            ->get();
        
        // Calculate quarter-wise data
        $quarterData = $this->calculateQuarterData($monthlyData);
        
        // Calculate YTD totals and averages
        $ytdData = $this->calculateYTDData($monthlyData);
        
        // Calculate trends and insights
        $insights = $this->calculateMonthwiseInsights($monthlyData);
        
        // Get comparison with previous year
        $previousYearData = $this->getPreviousYearComparison($selectedYear, $hrStaff);
        
        return view('recruiter.monthwise', compact(
            'selectedYear',
            'availableYears',
            'monthlyData',
            'quarterData',
            'ytdData',
            'insights',
            'previousYearData',
            'hrStaff'
        ));
    }
    
    public function getYearlyData($year)
    {
        $user   =   Auth::user();
        $hrStaff = $user->name;
        
        $monthlyData = RecruiterMonthlySummary::where('hr_staff', $hrStaff)
            ->whereRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) = ?', [$year])
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y')")
            ->get();
            
        if ($monthlyData->isEmpty()) {
            return response()->json(['error' => 'No data found for this year'], 404);
        }
        
        $quarterData = $this->calculateQuarterData($monthlyData);
        $ytdData = $this->calculateYTDData($monthlyData);
        $insights = $this->calculateMonthwiseInsights($monthlyData);
        
        return response()->json([
            'monthly_data' => $monthlyData,
            'quarter_data' => $quarterData,
            'ytd_data' => $ytdData,
            'insights' => $insights
        ]);
    }
    
    public function compareMonths(Request $request)
    {
        try {
            $user   =   Auth::user();
            $months = $request->months;
            $hrStaff = $user->name;
            
            if (count($months) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select at least 2 months to compare'
                ]);
            }
            
            $comparisonData = [];
            
            foreach ($months as $month) {
                $monthData = RecruiterMonthlySummary::where('month', $month)
                    ->where('hr_staff', $hrStaff)
                    ->first();
                    
                if ($monthData) {
                    $comparisonData[$month] = [
                        'summary' => $monthData,
                        'daily_totals' => json_decode($monthData->totals, true),
                        'unit_progress' => json_decode($monthData->unit_progress, true),
                        'overall_progress' => json_decode($monthData->overall_progress, true),
                        'overall_total' => $monthData->overall_total
                    ];
                }
            }
            
            // Calculate comparison metrics
            $comparisonMetrics = $this->calculateComparisonMetrics($comparisonData);
            
            return response()->json([
                'success' => true,
                'data' => $comparisonData,
                'metrics' => $comparisonMetrics
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error comparing months: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function calculateQuarterData($monthlyData)
    {
        $quarters = [
            'Q1' => ['months' => [], 'totals' => [], 'avg_progress' => 0],
            'Q2' => ['months' => [], 'totals' => [], 'avg_progress' => 0],
            'Q3' => ['months' => [], 'totals' => [], 'avg_progress' => 0],
            'Q4' => ['months' => [], 'totals' => [], 'avg_progress' => 0],
        ];
        
        foreach ($monthlyData as $data) {
            $month = Carbon::createFromFormat('F Y', $data->month);
            $quarter = 'Q' . ceil($month->month / 3);
            
            $quarters[$quarter]['months'][] = $data->month;
            $quarters[$quarter]['totals'][] = json_decode($data->totals, true);
            $quarters[$quarter]['avg_progress'] = ($quarters[$quarter]['avg_progress'] ?? 0) + $data->overall_total;
        }
        
        // Calculate quarter averages
        foreach ($quarters as $key => $quarter) {
            if (count($quarter['months']) > 0) {
                $quarters[$key]['avg_progress'] = $quarter['avg_progress'] / count($quarter['months']);
                
                // Calculate quarter totals
                $quarterTotals = [
                    'busi_profile' => 0,
                    'tele_marketing' => 0,
                    'cv_submitted' => 0,
                    'selected' => 0,
                    'payment_received' => 0
                ];
                
                foreach ($quarter['totals'] as $total) {
                    foreach ($quarterTotals as $metric => $value) {
                        $quarterTotals[$metric] += $total[$metric] ?? 0;
                    }
                }
                
                $quarters[$key]['quarter_totals'] = $quarterTotals;
            }
        }
        
        return $quarters;
    }
    
    private function calculateYTDData($monthlyData)
    {
        $ytdTotals = [
            'busi_profile' => 0,
            'tele_marketing' => 0,
            'client_empanelment' => 0,
            'cv_submitted' => 0,
            'interview_attended' => 0,
            'selected' => 0,
            'job_reported' => 0,
            'invoiced' => 0,
            'payment_received' => 0,
        ];
        
        $ytdProgress = 0;
        $monthCount = 0;
        
        foreach ($monthlyData as $data) {
            $monthTotals = json_decode($data->totals, true);
            
            foreach ($ytdTotals as $key => $value) {
                $ytdTotals[$key] += $monthTotals[$key] ?? 0;
            }
            
            $ytdProgress += $data->overall_total;
            $monthCount++;
        }
        
        return [
            'totals' => $ytdTotals,
            'avg_monthly_progress' => $monthCount > 0 ? $ytdProgress / $monthCount : 0,
            'months_count' => $monthCount
        ];
    }
    
    private function calculateMonthwiseInsights($monthlyData)
    {
        $insights = [
            'best_month' => null,
            'worst_month' => null,
            'trend' => 'stable',
            'recommendations' => []
        ];
        
        if ($monthlyData->isEmpty()) {
            return $insights;
        }
        
        // Find best and worst performing months
        $bestProgress = 0;
        $worstProgress = 100;
        
        foreach ($monthlyData as $data) {
            if ($data->overall_total > $bestProgress) {
                $bestProgress = $data->overall_total;
                $insights['best_month'] = [
                    'month' => $data->month,
                    'progress' => $data->overall_total,
                    'cv_submitted' => json_decode($data->totals, true)['cv_submitted'] ?? 0
                ];
            }
            
            if ($data->overall_total < $worstProgress) {
                $worstProgress = $data->overall_total;
                $insights['worst_month'] = [
                    'month' => $data->month,
                    'progress' => $data->overall_total,
                    'cv_submitted' => json_decode($data->totals, true)['cv_submitted'] ?? 0
                ];
            }
        }
        
        // Calculate trend
        if ($monthlyData->count() >= 2) {
            $firstMonth = $monthlyData->first()->overall_total;
            $lastMonth = $monthlyData->last()->overall_total;
            
            if ($lastMonth > $firstMonth * 1.1) {
                $insights['trend'] = 'improving';
            } elseif ($lastMonth < $firstMonth * 0.9) {
                $insights['trend'] = 'declining';
            } else {
                $insights['trend'] = 'stable';
            }
        }
        
        // Generate recommendations
        $totalCv = 0;
        $totalSelected = 0;
        
        foreach ($monthlyData as $data) {
            $totals = json_decode($data->totals, true);
            $totalCv += $totals['cv_submitted'] ?? 0;
            $totalSelected += $totals['selected'] ?? 0;
        }
        
        $conversionRate = $totalCv > 0 ? ($totalSelected / $totalCv * 100) : 0;
        
        if ($conversionRate < 20) {
            $insights['recommendations'][] = 'Focus on improving CV to interview conversion rate';
        }
        
        if ($bestProgress - $worstProgress > 20) {
            $insights['recommendations'][] = 'Consistency needed across months';
        }
        
        if ($totalCv / $monthlyData->count() < 150) {
            $insights['recommendations'][] = 'Increase CV submission rate to meet monthly targets';
        }
        
        return $insights;
    }
    
    private function getPreviousYearComparison($currentYear, $hrStaff)
    {
        $previousYear = $currentYear - 1;
        
        $currentYearData = RecruiterMonthlySummary::where('hr_staff', $hrStaff)
            ->whereRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) = ?', [$currentYear])
            ->get();
            
        $previousYearData = RecruiterMonthlySummary::where('hr_staff', $hrStaff)
            ->whereRaw('YEAR(STR_TO_DATE(CONCAT("01 ", month), "%d %M %Y")) = ?', [$previousYear])
            ->get();
        
        if ($currentYearData->isEmpty() || $previousYearData->isEmpty()) {
            return null;
        }
        
        $currentYTD = $this->calculateYTDData($currentYearData);
        $previousYTD = $this->calculateYTDData($previousYearData);
        
        $comparison = [];
        
        foreach ($currentYTD['totals'] as $key => $currentValue) {
            $previousValue = $previousYTD['totals'][$key] ?? 0;
            if ($previousValue > 0) {
                $comparison[$key] = (($currentValue - $previousValue) / $previousValue) * 100;
            } else {
                $comparison[$key] = $currentValue > 0 ? 100 : 0;
            }
        }
        
        return [
            'comparison' => $comparison,
            'current_ytd' => $currentYTD,
            'previous_ytd' => $previousYTD
        ];
    }
    
    private function calculateComparisonMetrics($comparisonData)
    {
        $metrics = [
            'highest_cv_month' => null,
            'highest_selection_month' => null,
            'most_consistent' => null,
            'variation' => 0
        ];
        
        $cvValues = [];
        $selectionValues = [];
        $progressValues = [];
        
        foreach ($comparisonData as $month => $data) {
            $totals = $data['daily_totals'];
            
            $cvValues[$month] = $totals['cv_submitted'] ?? 0;
            $selectionValues[$month] = $totals['selected'] ?? 0;
            $progressValues[$month] = $data['overall_total'];
        }
        
        // Find highest CV month
        arsort($cvValues);
        $metrics['highest_cv_month'] = array_key_first($cvValues);
        
        // Find highest selection month
        arsort($selectionValues);
        $metrics['highest_selection_month'] = array_key_first($selectionValues);
        
        // Calculate variation
        if (count($progressValues) > 1) {
            $avgProgress = array_sum($progressValues) / count($progressValues);
            $variance = 0;
            
            foreach ($progressValues as $value) {
                $variance += pow($value - $avgProgress, 2);
            }
            
            $metrics['variation'] = sqrt($variance / count($progressValues));
        }
        
        return $metrics;
    }
    
    // ==================== EXPORT FUNCTIONS ====================
    
    public function exportDailyExcel($month)
    {
        $user   =   Auth::user();
        $hrStaff = $user->name;
        $filename = 'Daily_Progress_' . str_replace(' ', '_', $month) . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new DailyProgressExport($month, $hrStaff), $filename);
    }
    
    public function exportMonthlyExcel($month)
    {
        $user   =   Auth::user();
        $hrStaff = $user->name;
        $filename = 'Monthly_Summary_' . str_replace(' ', '_', $month) . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new MonthlyProgressExport($month, $hrStaff), $filename);
    }
    
    public function exportMonthwiseExcel($year)
    {
        $user   =   Auth::user();
        $hrStaff = $user->name;
        $filename = 'Yearly_Comparison_' . $year . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new MonthwiseProgressExport($year, $hrStaff), $filename);
    }
    // In RecruiterController.php
    public function viewRecruiterDetails(Request $request)
    {
        $user = Auth::user();
        
        // Admin can view any recruiter, regular users can only view themselves
        if ($user->role === 'admin') {
            // Admin must specify which recruiter to view
            if (!$request->has('hr_staff')) {
                return redirect()->route('admin.recruiters.all-daily')
                    ->with('error', 'Please select a recruiter to view.');
            }
            $hrStaff = $request->hr_staff;
        } else {
            // Regular users can only view their own data
            $hrStaff = $user->name;
        }
        
        // Get selected month (default to current month)
        $selectedMonth = $request->get('month', Carbon::now()->format('F Y'));
        
        // Get recruiter's daily data
        $dailyData = RecruiterDailyProgress::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->orderBy('entry_day')
            ->get();
        
        // Get monthly summary
        $monthlySummary = RecruiterMonthlySummary::where('month', $selectedMonth)
            ->where('hr_staff', $hrStaff)
            ->first();
        
        // Calculate statistics
        $stats = $this->calculateRecruiterStats($dailyData, $monthlySummary);
        
        // Get previous months data for trend analysis
        $previousMonthsData = $this->getPreviousMonthsData($hrStaff, $selectedMonth);
        
        // Get available months for dropdown
        $availableMonths = RecruiterDailyProgress::select('month')
            ->where('hr_staff', $hrStaff)
            ->distinct()
            ->orderByRaw("STR_TO_DATE(CONCAT('01 ', month), '%d %M %Y') DESC")
            ->pluck('month')
            ->toArray();
        
        return view('recruiter.view-details', compact(
            'dailyData',
            'monthlySummary',
            'stats',
            'hrStaff',
            'selectedMonth',
            'availableMonths',
            'previousMonthsData',
            'user'
        ));
    }

    private function calculateRecruiterStats($dailyData, $monthlySummary)
    {
        if (!$monthlySummary) {
            return [
                'total_cvs' => 0,
                'total_interviews' => 0,
                'total_selected' => 0,
                'total_payments' => 0,
                'avg_cvs_per_day' => 0,
                'conversion_rate' => 0,
                'selection_rate' => 0,
                'days_worked' => 0,
                'performance_rating' => 'No Data'
            ];
        }
        
        $totals = json_decode($monthlySummary->totals, true);
        
        // Calculate days worked (days with any activity)
        $daysWorked = $dailyData->filter(function ($entry) {
            return $entry->cv_submitted > 0 || $entry->busi_profile > 0 || $entry->interview_attended > 0;
        })->count();
        
        // Calculate conversion rates
        $conversionRate = $totals['cv_submitted'] > 0 
            ? round(($totals['selected'] / $totals['cv_submitted']) * 100, 1)
            : 0;
        
        $selectionRate = $totals['interview_attended'] > 0
            ? round(($totals['selected'] / $totals['interview_attended']) * 100, 1)
            : 0;
        
        // Average CVs per working day
        $avgCvsPerDay = $daysWorked > 0
            ? round($totals['cv_submitted'] / $daysWorked, 1)
            : 0;
        
        // Performance rating
        $performanceRating = $this->getPerformanceRating($monthlySummary->overall_total);
        
        return [
            'total_cvs' => $totals['cv_submitted'] ?? 0,
            'total_interviews' => $totals['interview_attended'] ?? 0,
            'total_selected' => $totals['selected'] ?? 0,
            'total_payments' => $totals['payment_received'] ?? 0,
            'avg_cvs_per_day' => $avgCvsPerDay,
            'conversion_rate' => $conversionRate,
            'selection_rate' => $selectionRate,
            'days_worked' => $daysWorked,
            'performance_rating' => $performanceRating['label'],
            'performance_class' => $performanceRating['class']
        ];
    }

    private function getPreviousMonthsData($hrStaff, $currentMonth, $limit = 3)
    {
        // Get previous months data for trend analysis
        $previousMonths = [];
        $current = Carbon::createFromFormat('F Y', $currentMonth);
        
        for ($i = 1; $i <= $limit; $i++) {
            $month = $current->copy()->subMonths($i)->format('F Y');
            
            $summary = RecruiterMonthlySummary::where('month', $month)
                ->where('hr_staff', $hrStaff)
                ->first();
            
            if ($summary) {
                $previousMonths[$month] = [
                    'overall_progress' => $summary->overall_total,
                    'totals' => json_decode($summary->totals, true)
                ];
            }
        }
        
        return $previousMonths;
    }

    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'recruiter_id' => 'required|exists:users,id',
                'status' => 'required|in:Active,Inactive,Disable',
                'date_of_living' => 'nullable|date'
            ]);

            $recruiter = User::findOrFail($request->recruiter_id);
            
            // Update status
            $recruiter->status = $request->status;
            
            // Update DOL only when status is Inactive and DOL is provided
            if ($request->status == 'Inactive' && $request->date_of_living) {
                $recruiter->date_of_living = $request->date_of_living;
            } elseif ($request->status != 'Inactive') {
                // Clear DOL if status is not Inactive
                $recruiter->date_of_living = null;
            }
            
            $recruiter->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendPdfEmail(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:10240',
                'email' => 'required|email',
                'total_records' => 'required|integer',
                'record_type' => 'required|string'
            ]);

            // Get the uploaded file
            $file = $request->file('file');
            $recipientEmail = $request->email;
            $totalRecords = $request->total_records;
            $recordType = $request->record_type;
            
            // Get current user name
            $userName = auth()->user()->name ?? 'Recruitment Team';
            
            // Set title based on record type
            $title = ($recordType == 'recruiters') ? 'Recruiters List' : 'Staff List';
            
            // Send email with attachment
            \Mail::send([], [], function ($message) use ($file, $recipientEmail, $totalRecords, $title, $userName) {
                $message->to($recipientEmail)
                        ->subject($title . ' - ' . date('d-m-Y'))
                        ->html('
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <style>
                                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                    .header { background: #004085; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                                    .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                                    .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
                                    .summary { background: #e7f3ff; padding: 15px; border-left: 4px solid #004085; margin: 20px 0; }
                                    h3 { margin: 0; color: #004085; }
                                    p { margin: 10px 0; }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="header">
                                        <h2 style="margin:0; color:white;">' . $title . '</h2>
                                    </div>
                                    <div class="content">
                                        <p>Dear Sir/Madam,</p>
                                        
                                        <p>Please find attached the ' . strtolower($title) . ' PDF.</p>
                                        
                                        <div class="summary">
                                            <h3 style="margin-top:0;">Summary</h3>
                                            <p><strong>Total Records:</strong> ' . $totalRecords . '</p>
                                            <p><strong>Date:</strong> ' . date('d-m-Y') . '</p>
                                            <p><strong>Time:</strong> ' . date('h:i A') . '</p>
                                        </div>
                                        
                                        <p>The attached PDF contains detailed information about all ' . strtolower($title) . '.</p>
                                        
                                        <p>
                                            Best regards,<br>
                                            <strong>' . $userName . '</strong><br>
                                            Recruitment Team
                                        </p>
                                    </div>
                                    <div class="footer">
                                        <p style="margin:0;">This is an automated email. Please do not reply to this message.</p>
                                        <p style="margin:5px 0 0;">&copy; ' . date('Y') . ' Recruitment System. All rights reserved.</p>
                                    </div>
                                </div>
                            </body>
                            </html>
                        ');
                
                $message->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => 'application/pdf'
                ]);
            });

            // Log the activity
            \Log::info("PDF email sent to {$recipientEmail} with {$totalRecords} {$recordType}");

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!',
                'email' => $recipientEmail
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error sending PDF email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

