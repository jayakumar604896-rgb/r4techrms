<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /**
     * Display a listing of the offers.
     */
    public function index()
    {
        $offers = Offer::latest()->paginate(10);
        return view('offer.index', compact('offers'));
    }

    /**
     * Show the form for creating a new offer.
     */
    public function create()
    {
        return view('offer.add-offer');
    }

    /**
     * Store a newly created offer in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|string|max:50',
            'employer_name' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'designation_offered' => 'required|string|max:255',
            'employment_type' => 'required|string|max:100',
            'salary_offered' => 'required|string|max:100',
            'total_salary' => 'required|string|max:100',
            'food' => 'required|string|max:100',
            'accommodation' => 'required|string|max:100',
            'notice_period' => 'required|string|max:100',
            'recruiter_name' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:100',
            'no_of_positions' => 'required|integer|min:1',
            
            // Optional fields with validation
            'demand_ref_no' => 'nullable|string|max:100',
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            'client_agent_source' => 'nullable|string|max:255',
            'candidate_location' => 'nullable|string|max:255',
            'mode_of_interview' => 'nullable|string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'allowance_offered' => 'nullable|string|max:100',
            'accommodation_transport' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:100',
            'overtime' => 'nullable|string|max:100',
            'service_charge' => 'nullable|string|max:100',
            'certificate_special_req' => 'nullable|string|max:500',
            'required_cvs' => 'nullable|integer|min:0',
            'air_ticket' => 'nullable|string|max:100',
            'medical_test' => 'nullable|string|max:100',
            'certificate_attestation' => 'nullable|string|max:100',
            'pcc' => 'nullable|string|max:100',
            'passport_status' => 'nullable|string|max:100',
            'passport_validity' => 'nullable|string|max:100',
            'willing_to_work' => 'nullable|string|max:100',
            'other_information' => 'nullable|string|max:2000',
            
            // Array fields
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:50',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:50',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process checkbox arrays to JSON
        $data = $request->all();
        
        if ($request->has('mode_of_selection')) {
            $data['mode_of_selection'] = json_encode($request->mode_of_selection);
        }
        
        if ($request->has('type_of_hiring')) {
            $data['type_of_hiring'] = json_encode($request->type_of_hiring);
        }
        
        if ($request->has('advertisement_portals')) {
            $data['advertisement_portals'] = json_encode($request->advertisement_portals);
        }

        // Create the offer
        Offer::create($data);

        return redirect()->route('offer.index')
            ->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified offer.
     */
    public function show($id)
    {
        $offer  =   Offer::find($id);
        // Decode JSON fields for display
        $offer->mode_of_selection = $offer->mode_of_selection ? json_decode($offer->mode_of_selection) : [];
        $offer->type_of_hiring = $offer->type_of_hiring ? json_decode($offer->type_of_hiring) : [];
        $offer->advertisement_portals = $offer->advertisement_portals ? json_decode($offer->advertisement_portals) : [];
        
        return view('offer.view-offer', compact('offer'));
    }

    /**
     * Show the form for editing the specified offer.
     */
    public function edit($id)
    {
        $offer  =   Offer::find($id);
        // Decode JSON fields for form
        $offer->mode_of_selection = $offer->mode_of_selection ? json_decode($offer->mode_of_selection) : [];
        $offer->type_of_hiring = $offer->type_of_hiring ? json_decode($offer->type_of_hiring) : [];
        $offer->advertisement_portals = $offer->advertisement_portals ? json_decode($offer->advertisement_portals) : [];
        
        return view('offer.edit-offer', compact('offer'));
    }

    /**
     * Update the specified offer in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_type' => 'required|string|max:50',
            'employer_name' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'designation_offered' => 'required|string|max:255',
            'employment_type' => 'required|string|max:100',
            'salary_offered' => 'required|string|max:100',
            'total_salary' => 'required|string|max:100',
            'food' => 'required|string|max:100',
            'accommodation' => 'required|string|max:100',
            'notice_period' => 'required|string|max:100',
            'recruiter_name' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience' => 'required|string|max:100',
            'no_of_positions' => 'required|integer|min:1',
            
            // Optional fields
            'demand_ref_no' => 'nullable|string|max:100',
            'demand_open_date' => 'nullable|date',
            'demand_closed_date' => 'nullable|date|after_or_equal:demand_open_date',
            'client_agent_source' => 'nullable|string|max:255',
            'candidate_location' => 'nullable|string|max:255',
            'mode_of_interview' => 'nullable|string|max:100',
            'interview_date' => 'nullable|date',
            'interview_location' => 'nullable|string|max:255',
            'allowance_offered' => 'nullable|string|max:100',
            'accommodation_transport' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:100',
            'overtime' => 'nullable|string|max:100',
            'service_charge' => 'nullable|string|max:100',
            'certificate_special_req' => 'nullable|string|max:500',
            'required_cvs' => 'nullable|integer|min:0',
            'air_ticket' => 'nullable|string|max:100',
            'medical_test' => 'nullable|string|max:100',
            'certificate_attestation' => 'nullable|string|max:100',
            'pcc' => 'nullable|string|max:100',
            'passport_status' => 'nullable|string|max:100',
            'passport_validity' => 'nullable|string|max:100',
            'willing_to_work' => 'nullable|string|max:100',
            'other_information' => 'nullable|string|max:2000',
            
            // Array fields
            'mode_of_selection' => 'nullable|array',
            'mode_of_selection.*' => 'string|max:50',
            'type_of_hiring' => 'nullable|array',
            'type_of_hiring.*' => 'string|max:50',
            'advertisement_portals' => 'nullable|array',
            'advertisement_portals.*' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process checkbox arrays to JSON
        $data = $request->all();
        
        $data['mode_of_selection'] = $request->has('mode_of_selection') 
            ? json_encode($request->mode_of_selection) 
            : null;
            
        $data['type_of_hiring'] = $request->has('type_of_hiring') 
            ? json_encode($request->type_of_hiring) 
            : null;
            
        $data['advertisement_portals'] = $request->has('advertisement_portals') 
            ? json_encode($request->advertisement_portals) 
            : null;

        // Update the offer
        $offer->update($data);

        return redirect()->route('offer.index')
            ->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified offer from storage.
     */
    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('offer.index')
            ->with('success', 'Offer deleted successfully.');
    }

    /**
     * Export offers to CSV
     */
    public function export()
    {
        $fileName = 'offers_' . date('Y_m_d_H_i_s') . '.csv';
        $offers = Offer::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($offers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'User Type',
                'Demand Ref No',
                'Demand Open Date',
                'Demand Closed Date',
                'Recruiter Name',
                'Employer Name',
                'Job Location',
                'Industry',
                'Designation',
                'Employment Type',
                'Client/Agent/Source',
                'Qualification',
                'Experience',
                'Mode of Interview',
                'Food',
                'Accommodation & Transport',
                'Salary',
                'Working Hours',
                'Overtime',
                'Interview Date',
                'Interview Location',
                'Service Charge',
                'Certificate/Special Req',
                'Mode of Selection',
                'Type of Hiring',
                'No of Positions',
                'Candidate Location',
                'Required CVs',
                'Advertisement Portals',
                'Allowance',
                'Total Salary',
                'Accommodation',
                'Air Ticket',
                'Medical Test',
                'Certificate Attestation',
                'PCC',
                'Passport Status',
                'Passport Validity',
                'Willing to Work',
                'Notice Period',
                'Other Information',
                'Created At',
                'Updated At'
            ]);

            // Add data rows
            foreach ($offers as $offer) {
                fputcsv($file, [
                    $offer->id,
                    $offer->user_type,
                    $offer->demand_ref_no,
                    $offer->demand_open_date,
                    $offer->demand_closed_date,
                    $offer->recruiter_name,
                    $offer->employer_name,
                    $offer->job_location,
                    $offer->industry,
                    $offer->designation_offered,
                    $offer->employment_type,
                    $offer->client_agent_source,
                    $offer->qualification,
                    $offer->experience,
                    $offer->mode_of_interview,
                    $offer->food,
                    $offer->accommodation_transport,
                    $offer->salary_offered,
                    $offer->working_hours,
                    $offer->overtime,
                    $offer->interview_date,
                    $offer->interview_location,
                    $offer->service_charge,
                    $offer->certificate_special_req,
                    $offer->mode_of_selection,
                    $offer->type_of_hiring,
                    $offer->no_of_positions,
                    $offer->candidate_location,
                    $offer->required_cvs,
                    $offer->advertisement_portals,
                    $offer->allowance_offered,
                    $offer->total_salary,
                    $offer->accommodation,
                    $offer->air_ticket,
                    $offer->medical_test,
                    $offer->certificate_attestation,
                    $offer->pcc,
                    $offer->passport_status,
                    $offer->passport_validity,
                    $offer->willing_to_work,
                    $offer->notice_period,
                    $offer->other_information,
                    $offer->created_at,
                    $offer->updated_at
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get offers statistics for dashboard
     */
    public function statistics()
    {
        $totalOffers = Offer::count();
        $domesticOffers = Offer::where('user_type', 'Domestic')->count();
        $internationalOffers = Offer::where('user_type', 'International')->count();
        $latestOffers = Offer::latest()->take(5)->get();

        return response()->json([
            'total_offers' => $totalOffers,
            'domestic_offers' => $domesticOffers,
            'international_offers' => $internationalOffers,
            'latest_offers' => $latestOffers
        ]);
    }

    /**
     * Search offers
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $offers = Offer::where('employer_name', 'like', "%{$search}%")
            ->orWhere('designation_offered', 'like', "%{$search}%")
            ->orWhere('job_location', 'like', "%{$search}%")
            ->orWhere('recruiter_name', 'like', "%{$search}%")
            ->orWhere('demand_ref_no', 'like', "%{$search}%")
            ->latest()
            ->paginate(10);

        return view('offer.index', compact('offers'));
    }

    /**
     * Filter offers by criteria
     */
    public function filter(Request $request)
    {
        $query = Offer::query();

        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('industry')) {
            $query->where('industry', 'like', "%{$request->industry}%");
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $offers = $query->latest()->paginate(10);

        return view('offer.index', compact('offers'));
    }
}