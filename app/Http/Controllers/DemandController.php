<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use Auth;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    /**
     * Display a listing of the demands.
     */
    
    public function indexOld(Request $request)
    {
        $userType = Auth::user()->user_type;
        $query = Demand::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        );
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('demand_ref_no', 'like', "%{$search}%")
                ->orWhere('recruiter_name', 'like', "%{$search}%")
                ->orWhere('designation_offered', 'like', "%{$search}%")
                ->orWhere('job_location', 'like', "%{$search}%")
                ->orWhere('industry', 'like', "%{$search}%");
            });
        }
        
        $demands = $query->latest()->paginate(10);
        
        return view('demands.index', compact('demands'));
    }

public function index(Request $request)
    {
        $userType = Auth::user()->user_type;
        
        // Base query with user type filter
        $query = Demand::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        );
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('demand_ref_no', 'like', "%{$search}%")
                  ->orWhere('recruiter_name', 'like', "%{$search}%")
                  ->orWhere('designation_offered', 'like', "%{$search}%")
                  ->orWhere('job_location', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%");
            });
        }
        
        // Get status counts for badges
        $statusCounts = [
            'Working' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Working')->count(),
            
            'Hold' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Hold')->count(),
            
            'Rework' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Rework')->count(),
            
            'Reject' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Reject')->count(),
            
            'Other' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Other')->count(),
            
            'Closed' => Demand::when(
                in_array($userType, ['Domestic', 'International']),
                fn($query) => $query->where('user_type', $userType)
            )->where('status', 'Closed')->count(),
        ];
        
        $totalCount = Demand::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        )->count();
        
        // Paginate results
        $demands = $query->latest()->paginate(10);
        
        // Preserve query parameters for pagination
        if ($request->has('status')) {
            $demands->appends(['status' => $request->status]);
        }
        if ($request->has('search')) {
            $demands->appends(['search' => $request->search]);
        }
        
        return view('demands.index', compact('demands', 'statusCounts', 'totalCount'));
    }
    /**
     * Show the form for creating a new demand.
     */
    public function create()
    {
        return view('demands.create');
    }

    /**
     * Store a newly created demand in storage.
     */
    public function storeOld(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            // Basic Information
            'user_type' => 'required|in:Domestic,International',
            'demand_ref_no' => 'nullable|string|max:100|unique:demands,demand_ref_no',
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            
            // Position Details
            'recruiter_name' => 'required|string|max:255',
            'designation_offered' => 'required|string|max:255',
            'client_agent_source' => 'nullable|string|max:255',
            'no_of_positions' => 'required|integer|min:1',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'candidate_location' => 'nullable|string|max:255',
            
            // Interview & Selection Details
            'mode_of_interview' => 'nullable|string|max:100',
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:100',
            
            // Advertisement & Media
            'required_cvs' => 'nullable|integer|min:0',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:100',
            'other_information' => 'nullable|string',
        ]);

        // Process checkbox arrays
        $validated['mode_of_selection'] = $request->has('mode_of_selection') ? $request->mode_of_selection : null;
        $validated['type_of_hiring'] = $request->has('type_of_hiring') ? $request->type_of_hiring : null;
        $validated['advertisement_portals'] = $request->has('advertisement_portals') ? $request->advertisement_portals : null;

        // Set default status
        $validated['status'] = 'Open';

        // Create the demand
        Demand::create($validated);

        return redirect()->route('demands.index')
            ->with('success', 'Demand created successfully.');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'nullable|in:Domestic,International',
            'demand_ref_no' => 'nullable|string|max:100|unique:demands,demand_ref_no',
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            'recruiter_id' => 'required|exists:users,id', 
            
            'designation_offered' => 'nullable|string|max:255',
            'client_agent_source' => 'nullable|string|max:255',
            'no_of_positions' => 'nullable|integer|min:1',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'job_location' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id', 
            'candidate_location' => 'nullable|string|max:255',
            
            'main_keyword_design' => 'nullable|string|max:255',
            'main_keyword_qualification' => 'nullable|string|max:255',
            'main_keyword_experience' => 'nullable|string|max:255',
            'main_keyword_special_requirements' => 'nullable|string|max:255',
            'secondary_keyword_notice' => 'nullable|string|max:255',
            'secondary_keyword_salary_budget' => 'nullable|string|max:255',
            'secondary_keyword_additional_3' => 'nullable|string|max:255',
            'secondary_keyword_additional_4' => 'nullable|string|max:255',
            'sector_industry' => 'nullable|string|max:255',
            'a_sector_industry' => 'nullable|string|max:255',
            'candidate_location_requirement' => 'nullable|string|max:255',
            'a_candidate_location_requirement' => 'nullable|string|max:255',
             'food' => 'nullable|string|max:100',
            'accommodation_transport' => 'nullable|string|max:100',
            'salary' => 'nullable|string|max:100',
            'working_hours' => 'nullable|string|max:50',
            'overtime' => 'nullable|string|max:100',
            'service_charge' => 'nullable|string|max:100',
            'certificate_special_requirements' => 'nullable|string|max:500',
            
            'mode_of_interview' => 'nullable|string|max:100',
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:100',
            
            'required_cvs' => 'nullable|integer|min:0',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:100',
            'other_information' => 'nullable|string',
            'sub_division' => 'nullable|string',
            'other_industry' => 'nullable|string',
        ]);

        // Process checkbox arrays
        $validated['mode_of_selection'] = $request->has('mode_of_selection') ? $request->mode_of_selection : null;
        $validated['type_of_hiring'] = $request->has('type_of_hiring') ? $request->type_of_hiring : null;
        $validated['advertisement_portals'] = $request->has('advertisement_portals') ? $request->advertisement_portals : null;
        // Get recruiter name from user table
        $recruiter = \App\Models\User::find($validated['recruiter_id']);
        $validated['recruiter_name'] = $recruiter ? $recruiter->name : null;
        
        // Get sector/industry name from sector table
        $sector = \App\Models\Sector::find($validated['sector_id']);
        $validated['industry'] = $sector ? $sector->name : null;
        
        // Set default status
        $validated['status'] = 'Open';
        
        // Set created_by if you have auth
        if (auth()->check()) {
            $validated['created_by'] = auth()->id();
        }

        // Create the demand
        Demand::create($validated);

        return redirect()->route('demands.index')
            ->with('success', 'Demand created successfully.');
    }

    /**
     * Display the specified demand.
     */
    public function show(Demand $demand)
    {
        $demand->mode_of_selection = is_array($demand->mode_of_selection) 
        ? $demand->mode_of_selection 
        : json_decode($demand->mode_of_selection, true) ?? [];
    
        $demand->type_of_hiring = is_array($demand->type_of_hiring) 
            ? $demand->type_of_hiring 
            : json_decode($demand->type_of_hiring, true) ?? [];
        
        $demand->advertisement_portals = is_array($demand->advertisement_portals) 
            ? $demand->advertisement_portals 
            : json_decode($demand->advertisement_portals, true) ?? [];
        
        // If you want to load relationships
        $demand->load(['recruiter', 'sector', 'creator']);
        
        return view('demands.view', compact('demand'));
    }

    /**
     * Show the form for editing the specified demand.
     */
    public function edit(Demand $demand)
    {
        return view('demands.edit', compact('demand'));
    }

    /**
     * Update the specified demand in storage.
     */
    public function updateOld(Request $request, Demand $demand)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:Domestic,International',
            'demand_ref_no' => 'nullable|string|max:100|unique:demands,demand_ref_no,' . $demand->id,
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            'recruiter_name' => 'required|string|max:255',
            'designation_offered' => 'required|string|max:255',
            'client_agent_source' => 'nullable|string|max:255',
            'no_of_positions' => 'required|integer|min:1',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'candidate_location' => 'nullable|string|max:255',
            'mode_of_interview' => 'nullable|string|max:100',
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:100',
            'required_cvs' => 'nullable|integer|min:0',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:100',
            'other_information' => 'nullable|string',
            'status' => 'nullable|in:Open,Closed,In Progress',
        ]);

        // Process checkbox arrays
        $validated['mode_of_selection'] = $request->has('mode_of_selection') ? $request->mode_of_selection : null;
        $validated['type_of_hiring'] = $request->has('type_of_hiring') ? $request->type_of_hiring : null;
        $validated['advertisement_portals'] = $request->has('advertisement_portals') ? $request->advertisement_portals : null;

        $demand->update($validated);

        return redirect()->route('demands.index')
            ->with('success', 'Demand updated successfully.');
    }
  

    /**
    * Update the specified demand in storage.
    */
    public function update(Request $request, Demand $demand)
    {
        // Validate the request
        $validated = $request->validate([
            // Basic Information
            'user_type' => 'nullable|in:Domestic,International',
            'demand_ref_no' => 'nullable|string|max:100|unique:demands,demand_ref_no,' . $demand->id,
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            'recruiter_id' => 'nullable|exists:users,id',
            
            // Position Details
            'designation_offered' => 'nullable|string|max:255',
            'client_agent_source' => 'nullable|string|max:255',
            'no_of_positions' => 'nullable|integer|min:1',
            'qualification' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'job_location' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id',
            'candidate_location' => 'nullable|string|max:255',
            
            // Additional Benefits
            'food' => 'nullable|string|max:100',
            'accommodation_transport' => 'nullable|string|max:100',
            'salary' => 'nullable|string|max:100',
            'working_hours' => 'nullable|string|max:50',
            'overtime' => 'nullable|string|max:100',
            
            // Keywords Section
            'main_keyword_design' => 'nullable|string|max:255',
            'main_keyword_qualification' => 'nullable|string|max:255',
            'main_keyword_experience' => 'nullable|string|max:255',
            'main_keyword_special_requirements' => 'nullable|string|max:255',
            'a_sector_industry' => 'nullable|string|max:255',
            'a_candidate_location_requirement' => 'nullable|string|max:255',
            'secondary_keyword_notice' => 'nullable|string|max:255',
            'secondary_keyword_salary_budget' => 'nullable|string|max:255',
            'secondary_keyword_additional_3' => 'nullable|string|max:255',
            'secondary_keyword_additional_4' => 'nullable|string|max:255',
            'sector_industry' => 'nullable|string|max:255',
            'candidate_location_requirement' => 'nullable|string|max:255',
            
            // Interview & Selection Details
            'mode_of_interview' => 'nullable|string|max:100',
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:100',
            'service_charge' => 'nullable|string|max:100',
            'certificate_special_requirements' => 'nullable|string',
            
            // Advertisement & Media
            'required_cvs' => 'nullable|integer|min:0',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:100',
            'other_information' => 'nullable|string',
            'status' => 'nullable',
            'remarks' => 'nullable',
            'sub_division' => 'nullable|string',
            'other_industry' => 'nullable|string',
        ]);

        $validated['mode_of_selection'] = $request->has('mode_of_selection') ? $request->mode_of_selection : null;
        $validated['type_of_hiring'] = $request->has('type_of_hiring') ? $request->type_of_hiring : null;
        $validated['advertisement_portals'] = $request->has('advertisement_portals') ? $request->advertisement_portals : null;
        $today = \Carbon\Carbon::today();
        if (isset($validated['demand_closed_date']) && !is_null($validated['demand_closed_date'])) {
            $closedDate = \Carbon\Carbon::parse($validated['demand_closed_date']);
            if ($closedDate<=$today) {
                $validated['status'] = $request->status ?? '';
            } else {
                $validated['status'] = $request->status ?? '';
            }
        } else {
            
            $validated['status'] = $request->status ?? '';
        }
        $recruiter = \App\Models\User::find($validated['recruiter_id']);
        $validated['recruiter_name'] = $recruiter ? $recruiter->name : null;
        
        $sector = \App\Models\Sector::find($validated['sector_id']);
        $validated['industry'] = $sector ? $sector->name : null;

        $demand->update($validated);

        return redirect()->route('demands.index', $demand->id)
            ->with('success', 'Demand updated successfully.');
    }

    /**
     * Remove the specified demand from storage.
     */
    public function destroy(Demand $demand)
    {
        $demand->delete();

        return redirect()->route('demands.index')
            ->with('success', 'Demand deleted successfully.');
    }

    /**
     * Change demand status
     */
    public function changeStatus(Request $request, Demand $demand)
    {
        $request->validate([
            'status' => 'required|in:Open,Closed,In Progress'
        ]);

        $demand->update(['status' => $request->status]);

        return back()->with('success', 'Demand status updated successfully.');
    }
    public function print($id)
    {
        $demand = Demand::findOrFail($id);
        return view('demands.print', compact('demand'));
    }
	public function generateReferenceNumber(Request $request)
	{
	    try {
	        $request->validate([
	            'user_type' => 'required|in:Domestic,International',
	            'sub_division' => 'required_if:user_type,International'
	        ]);
	        
	        $userType = $request->user_type;
	        $subDivision = $request->sub_division;
	        
	        $refNo = Demand::generateDemandRefNo($userType, $subDivision);
	        
	        return response()->json([
	            'success' => true,
	            'ref_no' => $refNo
	        ]);
	        
	    } catch (\Exception $e) {
	        \Log::error('Demand Ref No Generation Error: ' . $e->getMessage());
	        
	        return response()->json([
	            'success' => false,
	            'message' => 'Failed to generate reference number: ' . $e->getMessage()
	        ], 500);
	    }
	}
}