<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

use App\Models\DailyProgress;
use App\Models\Weight;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class DailyProgressController extends Controller
{
    // Display all daily progress entries
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = DailyProgress::where('user_id', $user->id);
        
        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }
        
        if ($request->filled('month')) {
            $month = $request->month;
            $year = $request->filled('year') ? $request->year : date('Y');
            $query->whereYear('date', $year)->whereMonth('date', $month);
        }
        
        if ($request->filled('client_name')) {
            $query->where('client_name', 'like', '%' . $request->client_name . '%');
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        $progress = $query->paginate(20)->withQueryString();
        
        // Get summary for the filtered results
        $summary = $this->getSummary($query->get());
        
        return view('daily-progress.index', compact('progress', 'summary'));
    }
    
    // Show create form
    public function create()
    {
        $metricColumns = DailyProgress::getMetricColumns();
        $today = Carbon::today()->format('Y-m-d');
        
        return view('daily-progress.create', compact('metricColumns', 'today'));
    }
    
    // Store new daily progress
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'client_name' => 'nullable|string|max:255',
            'busi_profile_submission' => 'integer|min:0',
            'tele_marketing' => 'integer|min:0',
            'client_empanelment' => 'integer|min:0',
            'cv_submitted' => 'integer|min:0',
            'interview_organized' => 'integer|min:0',
            'interview_attended' => 'integer|min:0',
            'rejected' => 'integer|min:0',
            'selected' => 'integer|min:0',
            'job_reported' => 'integer|min:0',
            'invoiced' => 'integer|min:0',
            'payment_received' => 'integer|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check for duplicate entry for same date
        $existing = DailyProgress::where('user_id', Auth::id())
            ->whereDate('date', $request->date)
            ->first();
        
        if ($existing) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You already have an entry for this date. Please edit the existing entry.');
        }
        
        $progress = DailyProgress::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'client_name' => $request->client_name,
            'busi_profile_submission' => $request->busi_profile_submission ?? 0,
            'tele_marketing' => $request->tele_marketing ?? 0,
            'client_empanelment' => $request->client_empanelment ?? 0,
            'cv_submitted' => $request->cv_submitted ?? 0,
            'interview_organized' => $request->interview_organized ?? 0,
            'interview_attended' => $request->interview_attended ?? 0,
            'rejected' => $request->rejected ?? 0,
            'selected' => $request->selected ?? 0,
            'job_reported' => $request->job_reported ?? 0,
            'invoiced' => $request->invoiced ?? 0,
            'payment_received' => $request->payment_received ?? 0,
            'remarks' => $request->remarks,
        ]);
        
        return redirect()->route('daily-progress.index')
            ->with('success', 'Daily progress added successfully!');
    }
    
    // Show edit form
    public function edit(DailyProgress $dailyProgress)
    {
        // Check authorization
        if ($dailyProgress->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $metricColumns = DailyProgress::getMetricColumns();
        
        return view('daily-progress.edit', compact('dailyProgress', 'metricColumns'));
    }
    
    // Update existing progress
    public function update(Request $request, DailyProgress $dailyProgress)
    {
        // Check authorization
        if ($dailyProgress->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'client_name' => 'nullable|string|max:255',
            'busi_profile_submission' => 'integer|min:0',
            'tele_marketing' => 'integer|min:0',
            'client_empanelment' => 'integer|min:0',
            'cv_submitted' => 'integer|min:0',
            'interview_organized' => 'integer|min:0',
            'interview_attended' => 'integer|min:0',
            'rejected' => 'integer|min:0',
            'selected' => 'integer|min:0',
            'job_reported' => 'integer|min:0',
            'invoiced' => 'integer|min:0',
            'payment_received' => 'integer|min:0',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $dailyProgress->update([
            'date' => $request->date,
            'client_name' => $request->client_name,
            'busi_profile_submission' => $request->busi_profile_submission ?? 0,
            'tele_marketing' => $request->tele_marketing ?? 0,
            'client_empanelment' => $request->client_empanelment ?? 0,
            'cv_submitted' => $request->cv_submitted ?? 0,
            'interview_organized' => $request->interview_organized ?? 0,
            'interview_attended' => $request->interview_attended ?? 0,
            'rejected' => $request->rejected ?? 0,
            'selected' => $request->selected ?? 0,
            'job_reported' => $request->job_reported ?? 0,
            'invoiced' => $request->invoiced ?? 0,
            'payment_received' => $request->payment_received ?? 0,
            'remarks' => $request->remarks,
        ]);
        
        return redirect()->route('daily-progress.index')
            ->with('success', 'Daily progress updated successfully!');
    }
    
    // Delete progress entry
    public function destroy(DailyProgress $dailyProgress)
    {
        // Check authorization
        if ($dailyProgress->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $dailyProgress->delete();
        
        return redirect()->route('daily-progress.index')
            ->with('success', 'Daily progress deleted successfully!');
    }
    
    // Import from Excel/CSV
    public function import()
    {
        return view('daily-progress.import');
    }
    
    public function processImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);
        
        // Process file and import data
        // You would implement Excel/CSV parsing here
        
        return redirect()->route('daily-progress.index')
            ->with('success', 'Import completed successfully!');
    }
    
    // Export to Excel
    public function export(Request $request)
    {
        $user = Auth::user();
        $query = DailyProgress::where('user_id', $user->id);
        
        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        
        $data = $query->get();
        
        // Export to Excel (use Laravel Excel package)
        return response()->download($exportPath);
    }
    
    // Get summary statistics
    private function getSummary($data)
    {
        return [
            'total_entries' => $data->count(),
            'total_cv_submitted' => $data->sum('cv_submitted'),
            'total_interviews' => $data->sum('interview_organized') + $data->sum('interview_attended'),
            'total_selected' => $data->sum('selected'),
            'total_rejected' => $data->sum('rejected'),
            'total_payment' => $data->sum('payment_received'),
            'avg_selection_rate' => $data->avg('selection_percentage') ?? 0,
            'avg_rejection_rate' => $data->avg('rejection_percentage') ?? 0,
        ];
    }
}