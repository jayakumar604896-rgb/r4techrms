<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\PlacementType;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Client;
use App\Models\Agent;
use App\Models\ServiceProviderDetail;
use App\Models\StatusHistory;
use Storage;
use Illuminate\Support\Str;
use App\Models\CandidateFile;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class CandidateController extends Controller
{
    /**
     * Display a listing of the candidates.
     */
    public function indexOld()
    {
        $userType = Auth::user()->user_type;
        $candidates = Candidate::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        )->orderByDesc('created_at')->get();
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        return view('candidates.index', compact('candidates','placementtypes','industries','sectores'));
    }
    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file', // max 10MB
        ]);

        $file = $request->file('pdf');
        $filename = 'recruiters_' . time() . '_' . Str::random(8) . '.pdf';
        
        $path = $file->storeAs('temp', $filename, 'public');
        
        $url = asset('storage/' . $path);
        
        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename
        ]);
    }
    public function index()
    {
        $userType = Auth::user()->user_type;
        $query = Candidate::query();
        if (in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', $userType);
        }
        $candidates = $query->orderByDesc('created_at')->get();
        $candidateCounts = [
            'total' => $candidates->where('user_type','Domestic')->count(),
            'inprogress' => $candidates->where('user_type','Domestic')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type','Domestic')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type','Domestic')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type','Domestic')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type','Domestic')->where('status', 'Back Out')->count(),
        ];
        $internationalcandidateCounts = [
            'total' => $candidates->where('user_type','International')->count(),
            'inprogress' => $candidates->where('user_type','International')->where('status', 'Inprocess')->count(),
            'selected' => $candidates->where('user_type','International')->where('status', 'Selected')->count(),
            'waitinglist' => $candidates->where('user_type','International')->where('status', 'Waitting List')->count(),
            'rejected' => $candidates->where('user_type','International')->where('status', 'Rejects')->count(),
            'backout' => $candidates->where('user_type','International')->where('status', 'Back Out')->count(),
        ];
        
        $placementtypes = PlacementType::pluck('name','id');
        $industries = Industry::pluck('name','id');
        $sectores = Sector::pluck('name','id');
        
        return view('candidates.index', compact('candidates', 'candidateCounts', 'placementtypes', 'industries', 'sectores','internationalcandidateCounts'));
    }
	public function globalSearch(Request $request)
	{
	    $query = $request->get('query');
	    $results = [];
	    
	    if (strlen($query) >= 2) {
	        $candidates = Candidate::where('candidate_name', 'like', "%{$query}%")
	            ->orWhere('email', 'like', "%{$query}%")
	            ->orWhere('mobile', 'like', "%{$query}%")
	            ->limit(10)
	            ->get();
	        
	        $results['candidates'] = $candidates->map(function($candidate) {
	            return [
	                'id' => $candidate->id,
	                'candidate_name' => $candidate->candidate_name,
	                'email' => $candidate->email,
	                'mobile' => $candidate->mobile,
	                'designation' => $candidate->designation,
	                'url' => route('candidates.show', $candidate->id)
	            ];
	        });
	        
	        $results['total'] = Candidate::where('candidate_name', 'like', "%{$query}%")
	            ->orWhere('email', 'like', "%{$query}%")
	            ->orWhere('mobile', 'like', "%{$query}%")
	            ->count();
	    }
	    
	    return response()->json($results);
	}
    /**
     * Show the form for creating a new candidate.
     */
    public function create()
    {
        return view('candidates.add-candidate');
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'code_no' => 'nullable|string|unique:candidates',
            'date_of_submission' => 'required|date',
            'dob' => 'nullable|date',
            'age' => 'nullable|integer',
            'email' => 'nullable|email',
            'contact_number' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
            'sector_id' => 'nullable|exists:sectors,id',
            'industry_id' => 'nullable|exists:industries,id',
            'qualification' => 'nullable|string|max:255',
            'total_experience' => 'nullable|numeric|min:0',
            'placement_type_id' => 'required|exists:placement_types,id',
            'post_location_applied' => 'nullable|string|max:255',
            'current_stay' => 'nullable|string|max:255',
            'ctc' => 'nullable|numeric|min:0',
            'notice_period' => 'nullable|integer|min:0',
            'user_type' => 'required|in:Domestic,International',
            'remarks' => 'nullable|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'passport_number' => 'nullable|required_if:user_type,International|string|max:100',
            'passport_valid_date' => 'nullable|required_if:user_type,International|date',
            'indian_experience' => 'nullable|required_if:user_type,International|numeric|min:0',
            'foreign_experience' => 'nullable|required_if:user_type,International|numeric|min:0',
            'sub_division' => 'nullable|string|max:255',
        ]);
        $photoPath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('resume/'), $filename);
            $photoPath = 'resume/'.$filename;
        }
        $candidateData = $request->all();
        if ($request->sector_id === '13') {
            $sector = \App\Models\Sector::firstOrCreate([
                'name' => $request->other_sector
            ]);
            
            $sectorId = $sector->id;
        } else {
            $sectorId = $request->sector_id;
        }
        if ($request->industry_id === '8') {
            $industry = \App\Models\Industry::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $industryId = $industry->id;
        } else {
            $industryId = $request->industry_id;
        }
        $candidateData = [
            'candidate_name' => $validated['first_name'] . ' ' . ($validated['last_name'] ?? ''),
            'age' => $validated['age'] ?? null,
            'DOS' => $validated['date_of_submission'] ?? null,
            'resume' => $photoPath ?? null,
            'code_no' => $validated['code_no'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'passport_details' => '',
            'qualfn' => $validated['qualification'] ?? null,
            'sector' => $sectorId ?? null,
            'industry' => $industryId ?? null, 
            'designation' => $validated['designation'] ?? null,
            'placement_type' => $validated['placement_type_id'] ?? null, 
            'total_exp' => $validated['total_experience'] ?? null,
            'post_location_applied' => $validated['post_location_applied'] ?? null,
            'current_stay' => $validated['current_stay'] ?? null,
            'ctc' => $validated['ctc'] ?? null,
            'notice_period' => $validated['notice_period'] ?? null,
            'mobile' => $validated['contact_number'] ?? null,
            'user_type' => $validated['user_type'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'email' => $validated['email'] ?? null,
            'passport_number' => $request->user_type === 'International' ? $request->passport_number : null,
            'passport_valid_date' => $request->user_type === 'International' ? $request->passport_valid_date : null,
            'indian_experience' => $request->user_type === 'International' ? $request->indian_experience : null,
            'foreign_experience' => $request->user_type === 'International' ? $request->foreign_experience : null,
            'sub_division' => $request->user_type === 'International' ? $request->sub_division : null,

            // 'emp_code'=>Candidate::generateSimpleEmpCode(),
        ];
        // $candidateData['resume'] = $photoPath;
        Candidate::create($candidateData);

        return redirect()->route('candidates.index')->with('success', 'Candidate added successfully!');
    }
    /**
     * Display the specified candidate.
     */
    public function show($id)
    {
        // $candidate      =   Candidate::findOrFail($id);
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        $clients        =   Client::where('status',1)->get();
        // $statusHistory  =   StatusHistory::get();
         $candidate = Candidate::with(['statusHistory', 'statusHistory.company', 'statusHistory.updatedByUser'])->findOrFail($id);
        
        $statusHistory = $candidate->statusHistory()->latest()->get();
        return view('candidates/view-candidate', compact('candidate','placementtypes','industries','sectores','clients','statusHistory'));
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('candidates.edit-candidate', compact('candidate'));
    }

    /**
     * Update the specified candidate in storage.
     */
    public function updateOld(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'company_serial_number'   => 'nullable|string|max:255',
            'code_no'                 => 'nullable|string|max:255',
            'DOS'                     => 'nullable|string',
            'candidate_name'          => 'required|string|max:255',
            'resume'                  => 'file|mimes:pdf,doc,docx|max:2048',
            'dob'                     => 'nullable|date',
            'age'                     => 'nullable|integer',
            'passport_details'        => 'nullable|string|max:255',
            'qualfn'                  => 'nullable|string|max:255',
            'sector'                  => 'nullable|string|max:255',
            'industry'                => 'nullable|string|max:255',
            'designation'             => 'nullable|string|max:255',
            'placement_type'          => 'nullable|string|max:255',
            'total_exp'               => 'nullable|string|max:255',
            'post_location_applied'   => 'nullable|string|max:255',
            'current_stay'            => 'nullable|string|max:255',
            'ctc'                     => 'nullable|string|max:255',
            'notice_period'           => 'nullable|string|max:255',
            'mobile'                  => 'nullable|string|max:20',
            'remarks'                 => 'nullable|string',
            'user_type'               => 'nullable|string|max:50',
            'field1'                  => 'nullable|string|max:255',
            'field2'                  => 'nullable|string|max:255',
            'field3'                  => 'nullable|string|max:255',
            'field4'                  => 'nullable|string|max:255',
        ]);

         $photoPath = $candidate->resume;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('resume/'), $filename);
            $photoPath = 'resume/'.$filename;
        }
        $candidateData = $request->all();
        if ($request->sector_id === '13') {
            $sector = \App\Models\Sector::firstOrCreate([
                'name' => $request->other_sector
            ]);
            
            $sectorId = $sector->id;
        } else {
            $sectorId = $request->sector_id;
        }
        if ($request->industry_id === '8') {
            $industry = \App\Models\Industry::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $industryId = $industry->id;
        } else {
            $industryId = $request->industry_id;
        }
        $candidateData['resume'] = $photoPath;
        $candidateData['sector_id'] = $sectorId;
        $candidateData['industry_id'] = $industryId;
        $candidate->update($candidateData);

        return redirect()->route('candidates.index')
                         ->with('success', 'Candidate updated successfully!');
    }


    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $validated = $request->validate([
            'candidate_name' => 'required|string|max:255',
            'code_no' => 'nullable|string|unique:candidates,code_no,' . $candidate->id,
            'DOS' => 'required|date',
            'dob' => 'nullable|date',
            'age' => 'nullable|integer',
            'email' => 'nullable|email',
            'mobile' => 'required|string|max:20',
            'designation' => 'nullable|string|max:255',
            'sector_id' => 'nullable|string|max:255', 
            'industry_id' => 'nullable|string|max:255',
            'qualfn' => 'nullable|string|max:255',
            'total_exp' => 'nullable|string|max:255',
            'placement_type' => 'required|string|max:255',
            'post_location_applied' => 'nullable|string|max:255',
            'current_stay' => 'nullable|string|max:255',
            'ctc' => 'nullable|string|max:255',
            'notice_period' => 'nullable|string|max:255',
            'user_type' => 'required|in:Domestic,International,Other',
            'remarks' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', 
            'passport_details' => 'nullable|string|max:255',
            'company_serial_number' => 'nullable|string|max:255',
            'passport_number' => 'nullable|required_if:user_type,International|string|max:100',
            'passport_valid_date' => 'nullable|required_if:user_type,International|date',
            'indian_experience' => 'nullable|required_if:user_type,International|numeric|min:0',
            'foreign_experience' => 'nullable|required_if:user_type,International|numeric|min:0',
            'sub_division' => 'nullable',
        ]);

        // Handle resume upload if new file is provided
        $photoPath = $candidate->resume; // Keep existing resume by default
        
        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($candidate->resume && file_exists(public_path($candidate->resume))) {
                unlink(public_path($candidate->resume));
            }
            
            $file = $request->file('resume');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('resume/'), $filename);
            $photoPath = 'resume/'.$filename;
        } elseif ($request->has('remove_resume') && $request->remove_resume == '1') {
            // Remove resume if flag is set
            if ($candidate->resume && file_exists(public_path($candidate->resume))) {
                unlink(public_path($candidate->resume));
            }
            $photoPath = null;
        }
        if ($request->sector_id === '13') {
            $sector = \App\Models\Sector::firstOrCreate([
                'name' => $request->other_sector
            ]);
            
            $sectorId = $sector->id;
        } else {
            $sectorId = $request->sector_id;
        }
        if ($request->industry_id === '8') {
            $industry = \App\Models\Industry::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $industryId = $industry->id;
        } else {
            $industryId = $request->industry_id;
        }
        // Prepare candidate data for update
        $candidateData = [
            'candidate_name' => $validated['candidate_name'] ?? null,
            'age' => $validated['age'] ?? null,
            'DOS' => $validated['DOS'] ?? null,
            'resume' => $photoPath ?? null,
            'code_no' => $validated['code_no'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'passport_details' => $validated['passport_details'] ?? $candidate->passport_details,
            'qualfn' => $validated['qualfn'] ?? null,
            'sector' => $sectorId ?? null,
            'industry' => $industryId ?? null,
            'designation' => $validated['designation'] ?? null,
            'placement_type' => $validated['placement_type'] ?? null,
            'total_exp' => $validated['total_exp'] ?? null,
            'post_location_applied' => $validated['post_location_applied'] ?? null,
            'current_stay' => $validated['current_stay'] ?? null,
            'ctc' => $validated['ctc'] ?? null,
            'notice_period' => $validated['notice_period'] ?? null,
            'mobile' => $validated['mobile'] ?? null,
            'user_type' => $validated['user_type'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'email' => $validated['email'] ?? null,
            'company_serial_number' => $validated['company_serial_number'] ?? $candidate->company_serial_number,
            'passport_number' => $request->user_type === 'International' ? $request->passport_number : null,
            'passport_valid_date' => $request->user_type === 'International' ? $request->passport_valid_date : null,
            'indian_experience' => $request->user_type === 'International' ? $request->indian_experience : null,
            'foreign_experience' => $request->user_type === 'International' ? $request->foreign_experience : null,
            'sub_division' => $request->user_type === 'International' ? $request->sub_division : null,
            'field3' => $validated['field3'] ?? $candidate->field3,
            'field4' => $validated['field4'] ?? $candidate->field4,
            // emp_code is not updated as it's auto-generated
        ];

        $candidate->update($candidateData);

        return redirect()->route('candidates.show', $candidate->id)->with('success', 'Candidate updated successfully!');
    }

    /**
     * Remove the specified candidate from storage.
     */
    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return redirect()->route('candidates.index')->with('success', 'Candidate deleted successfully!');
    }

    public function sendToRecruiter(Request $request)
    {
        $request->validate([
            'send_to' => 'required',
            'candidate_ids' => 'required',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);
        try {
            if($request->rec_type == "candidate") {
                $recruiter = Candidate::find($request->send_to);
                $email  =   $recruiter->email;
            }
            else if($request->rec_type == "client") {
                $recruiter = Client::find($request->send_to);
                $email  =   $recruiter->email_id;
            }
            else if($request->rec_type == "agent") {
                $recruiter = Agent::find($request->send_to);
                $email  =   $recruiter->email_id;
            }
            else if($request->rec_type == "service provider") {
                $recruiter = ServiceProviderDetail::find($request->send_to);
                $email  =   $recruiter->email_id;
            }
            else {
                return response()->json(['error' => 'Invalid recruiter type'], 400);
            }
            $candidateIds = explode(',', $request->candidate_ids);
            $candidates = Candidate::whereIn('id', $candidateIds)->get();

            // Check if any candidates have resumes
            $candidatesWithResumes = $candidates->filter(function($candidate) {
                return $candidate->resume && Storage::exists('public/' . $candidate->resume);
            });

            // Prepare email data
            $emailData = [
                'recruiter_name' => $recruiter->name,
                'subject' => $request->subject,
                'message_content' => $request->message,
                'candidates' => $candidates,
                'total_candidates' => $candidates->count(),
                'resumes_count' => $candidatesWithResumes->count()
            ];

            // Send email with attachments
            Mail::send('emails.candidates-to-recruiter', $emailData, function($message) use ($recruiter, $request, $email, $candidatesWithResumes) {
                $message->to($email)
                        ->subject($request->subject);
                
                // Attach resumes
                foreach ($candidatesWithResumes as $candidate) {
                    $resumePath = storage_path('app/public/' . $candidate->resume);
                    if (file_exists($resumePath)) {
                        $message->attach($resumePath, [
                            'as' => $candidate->candidate_name . '_Resume.' . pathinfo($resumePath, PATHINFO_EXTENSION),
                            'mime' => mime_content_type($resumePath)
                        ]);
                    }
                }
            });

            return redirect()->route('candidates.index')
                ->with('success', 'Candidates sent successfully to ' . $recruiter->email . 
                       ' with ' . $candidatesWithResumes->count() . ' resume(s) attached');

        } catch (\Exception $e) {
            \Log::error('Failed to send candidates: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to send candidates: ' . $e->getMessage());
        }
    }
    public function getRecipientsByType(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'type' => 'required|string|in:candidate,client,agent,service provider,recruiter'
            ]);
            
            $type = $request->input('type');
            $recipients = [];
            
            // Get recipients based on type
            switch ($type) {
                case 'candidate':
                    // Get candidates with email
                    $recipients = Candidate::select('id', 'candidate_name as name', 'email', 'mobile as phone')
                        ->whereNotNull('email')
                        ->where('email', '!=', '')
                        ->where('email', 'NOT LIKE', '%@example.com') // Filter out example emails
                        ->orderBy('candidate_name', 'asc')
                        ->get();
                    break;
                    
                case 'client':
                    // Check if Client model exists, otherwise use User model with client role
                    if (class_exists('App\\Models\\Client')) {
                        $recipients = Client::select('id', 'contact_person as name', 'email_id as email', 'mobile_number as phone')
                            ->whereNotNull('email_id')
                            ->where('email_id', '!=', '')
                            ->orderBy('contact_person', 'asc')
                            ->get();
                    } 
                    break;
                    
                case 'agent':
                    // Check if Agent model exists
                    if (class_exists('App\\Models\\Agent')) {
                        $recipients = Agent::select('id', 'agent_name as name', 'email_id as email', 'mobile_number as phone')
                            ->whereNotNull('email_id')
                            ->where('email_id', '!=', '')
                            ->orderBy('agent_name', 'asc')
                            ->get();
                    } 
                    break;
                    
                case 'service provider':
                    // Check if ServiceProvider model exists
                    if (class_exists('App\\Models\\ServiceProvider')) {
                        $recipients = ServiceProvider::select('id', 'name', 'email_id as email', 'mobile_number as phone')
                            ->whereNotNull('email_id')
                            ->where('email_id', '!=', '')
                            ->orderBy('name', 'asc')
                            ->get();
                    }
                    break;
                    
                case 'recruiter':
                default:
                    // Default to users (recruiters)
                    $recipients = Candidate::select('id', 'candidate_name as name', 'email', 'mobile as phone')
                        ->whereNotNull('email')
                        ->where('email', '!=', '')
                        ->where('email', 'NOT LIKE', '%@example.com') // Filter out example emails
                        ->orderBy('candidate_name', 'asc')
                        ->get();
                    break;
            }
            
            // Format the response
            $formattedRecipients = $recipients->map(function ($recipient) {
                return [
                    'id' => $recipient->id,
                    'name' => $recipient->name ?? 'Unknown',
                    'email' => $recipient->email ?? '',
                    'phone' => $recipient->phone ?? '',
                    'display_text' => $recipient->name . ' (' . $recipient->email . ')'
                ];
            });
            
            return response()->json([
                'success' => true,
                'message' => 'Recipients loaded successfully',
                'data' => $formattedRecipients,
                'count' => count($formattedRecipients)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting recipients by type: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading recipients: ' . $e->getMessage(),
                'data' => [],
                'count' => 0
            ], 500);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive,Disable,Inprocess,Waitting List,Rejects,Selected,Back Out',
            'company_id' => 'nullable|exists:clients,id',
            'interview_date' => 'nullable|date',
            'remarks' => 'nullable|string|max:500',
        ]);

        $candidate = Candidate::findOrFail($id);
        
        // Update candidate status
        $candidate->status = $request->status;
        
        // Additional fields for Inprocess status
        if ($request->status === 'Inprocess') {
            $candidate->company_id = $request->company_id;
            $candidate->interview_date = $request->interview_date;
        } elseif ($request->status === 'Selected') {
            $candidate->selected_at = now();
        }
        
        $candidate->save();

        // Create status history record
        StatusHistory::create([
            'candidate_id' => $candidate->id,
            'status' => $request->status,
            'company_id' => $request->company_id,
            'interview_date' => $request->interview_date,
            'remarks' => $request->remarks,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('candidates.show', $candidate->id)
            ->with('success', 'Candidate status updated successfully!');
    }

    // Function to get status history (in show method)
    // public function show($id)
    // {
    //     $candidate = Candidate::with(['statusHistory', 'statusHistory.company', 'statusHistory.updatedByUser'])
    //         ->findOrFail($id);
        
    //     $statusHistory = $candidate->statusHistory()->latest()->get();
        
    //     return view('candidates.show', compact('candidate', 'statusHistory'));
    // }

    public function sendPdfEmailOld(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required',
                'total_candidates' => 'required|integer'
            ]);

            // Get the uploaded file
            $file = $request->file('file');
            
            // You can customize the recipient email here
            $recipientEmail = 'recipient@example.com'; // Change this to dynamic email
            
            // Send email with attachment
            \Mail::send([], [], function ($message) use ($file, $request, $recipientEmail) {
                $message->to($recipientEmail)
                        ->subject('Candidates List - ' . date('d-m-Y'))
                        ->html('
                            <h3>Candidates List</h3>
                            <p>Please find attached the candidates list PDF.</p>
                            <p><strong>Total Candidates:</strong> ' . $request->total_candidates . '</p>
                            <p><strong>Date:</strong> ' . date('d-m-Y') . '</p>
                            <br>
                            <p>Best regards,<br>' . auth()->user()->name . '</p>
                        ');
                
                $message->attach($file->getRealPath(), [
                    'as' => $file->getClientOriginalName(),
                    'mime' => 'application/pdf'
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error sending PDF email: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function sendPdfEmail(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:10240',
                'email' => 'required|email',
                'total_candidates' => 'required|integer'
            ]);

            // Get the uploaded file
            $file = $request->file('file');
            $recipientEmail = $request->email;
            $totalCandidates = $request->total_candidates;
            
            // Get current user name
            $userName = auth()->user()->name ?? 'Recruitment Team';
            
            // Send email with attachment
            \Mail::send([], [], function ($message) use ($file, $recipientEmail, $totalCandidates, $userName) {
                $message->to($recipientEmail)
                        ->subject('Candidates List - ' . date('d-m-Y'))
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
                                        <h2 style="margin:0; color:white;">Candidates List</h2>
                                    </div>
                                    <div class="content">
                                        <p>Dear Sir/Madam,</p>
                                        
                                        <p>Please find attached the candidates list PDF.</p>
                                        
                                        <div class="summary">
                                            <h3 style="margin-top:0;">Summary</h3>
                                            <p><strong>Total Candidates:</strong> ' . $totalCandidates . '</p>
                                            <p><strong>Date:</strong> ' . date('d-m-Y') . '</p>
                                            <p><strong>Time:</strong> ' . date('h:i A') . '</p>
                                        </div>
                                        
                                        <p>The attached PDF contains detailed information about all candidates including:</p>
                                        <ul>
                                            <li>Candidate names and experience</li>
                                            <li>Industry and designation</li>
                                            <li>Qualification and mobile numbers</li>
                                            <li>CV codes and current status</li>
                                        </ul>
                                        
                                        <p>If you have any questions or need further information, please don\'t hesitate to contact us.</p>
                                        
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
            \Log::info("PDF email sent to {$recipientEmail} with {$totalCandidates} candidates");

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


    public function getFiles(Request $request)
    {
        $candidateId = $request->candidate_id;

        $files = CandidateFile::with('candidate:id,candidate_name')
                    ->where('candidate_id', $candidateId)
                    ->get()
                    ->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'candidate_name' => $file->candidate->candidate_name,
                            'file_title' => $file->file_title,
                            'file_name' => basename($file->file_path),
                            'file_path' => $file->file_path,
                            'uploaded_date' => $file->created_at->format('d M, Y'), // Added for display
                        ];
                    });

        return response()->json([
            'files' => $files,
        ]);
    }

    public function sendToRecruiterNew(Request $request)
    {
        $request->validate([
            'rec_type' => 'required',
            'send_to' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'candidate_ids' => 'required',
            'file_ids' => 'nullable|string',
        ]);

        $candidateIds = explode(',', $request->candidate_ids);
        $fileIds = $request->file_ids ? explode(',', $request->file_ids) : [];

        // Get candidate data (you may already have this logic)
        $candidates = Candidate::whereIn('id', $candidateIds)->get();

        // Generate PDF/Excel attachment (your existing logic)
        // For example, call a method to generate candidate list PDF
        $pdf = $this->generateCandidatePDF($candidates); // implement this

        // Prepare email
        $emailData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'candidates' => $candidates,
        ];

        Mail::send([], [], function ($mail) use ($request, $pdf, $fileIds) {
            $mail->to($request->send_to)
                ->subject($request->subject)
                ->setBody($request->message, 'text/html');

            // Attach the generated PDF
            $mail->attachData($pdf->output(), 'candidates_list.pdf', [
                'mime' => 'application/pdf',
            ]);

            // Attach selected files from CandidateFile
            if (!empty($fileIds)) {
                $files = CandidateFile::whereIn('id', $fileIds)->get();
                foreach ($files as $file) {
                    $fullPath = storage_path('app/public/' . $file->file_path);
                    if (file_exists($fullPath)) {
                        $mail->attach($fullPath, [
                            'as' => $file->file_title ?: basename($file->file_path),
                            'mime' => mime_content_type($fullPath)
                        ]);
                    }
                }
            }
        });

        return redirect()->back()->with('success', 'Email sent successfully with attachments.');
    }

    private function generateCandidatePDF($candidates)
    {
        $pdf = \PDF::loadView('pdf.candidates', compact('candidates'));
        return $pdf;
    }
}
