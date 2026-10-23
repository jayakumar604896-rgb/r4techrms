<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agent;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\PlacementType;
use App\Models\Industry;
use App\Models\Sector;
use App\Models\Bussiness;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class AgentController extends Controller
{
    /**
     * Display a listing of the agents.
     */
    public function index(Request $request)
    {
        $userType = Auth::user()->user_type;
		$type = $request->get('type', 'all');
        $query = Agent::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        );
 		if (in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', $userType);
        }
        
        // Apply type filter from URL (for admin/super admin)
        if ($type !== 'all' && !in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', ucfirst($type));
        }
        
        $agents = $query->orderByDesc('created_at')->get();
        
        // Get counts for stats
        $counts = [
            'all' => Agent::count(),
            'Active' => Agent::where('status', 1)->count(),
            'Inactive' => Agent::where('status', 2)->count(),
            'International' => Agent::where('user_type', 'International')->count(),
            'Domestic' => Agent::where('user_type', 'Domestic')->count(),
        ];
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        return view('agents.index', compact('agents','placementtypes','industries','sectores'));
    }

    /**
     * Show the form for creating a new agent.
     */
    public function create()
    {
        return view('agents.add-agent');
    }

    /**
     * Store a newly created agent in storage.
     */
 public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_company_name' => 'nullable|string|max:255',
            'type_business' => 'nullable|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'email_id' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'user_type' => 'nullable|string|max:255',
            'mea_number' => 'nullable|string|max:255',
            'licence_number' => 'nullable|string|max:255',
            'agent_name_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:255',
            'alternate_number_two' => 'nullable|string|max:255',
            'email_id_two' => 'nullable|string|max:255',
            'status' => 'nullable|in:1,2',
            'agent_business_profile_attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'sub_division' => 'nullable|string|max:255',
        ]);

        

        $photoPath = null;
        if ($request->hasFile('agent_business_profile_attachment')) {
            $file = $request->file('agent_business_profile_attachment');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('agent/'), $filename);
            $photoPath = 'agent/'.$filename;
        }

        $agentData = $request->all();
        $agentData['agent_business_profile_attachment']  =   $photoPath;
        if ($request->type_business === 'Other') {
            $industry = \App\Models\Bussiness::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $agentData['type_business'] = $industry->name;
        } else {
            $agentData['type_business'] = $request->type_business;
        }
        Agent::create($agentData);

        return redirect()->route('agents.index')->with('success', 'Agent added successfully!');
    }
    /**
     * Display the specified agent.
     */
    public function show($id)
    {
        $agent          =   Agent::findOrFail($id);
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        return view('agents/view-agent', compact('agent','placementtypes','industries','sectores'));
    }
    public function printClient($id)
    {
        $agent          =   Agent::findOrFail($id);
        return view('agents/print', compact('agent'));
    }

    /**
     * Show the form for editing the specified agent.
     */
    public function edit($id)
    {
        $agent = Agent::findOrFail($id);
        return view('agents.edit-agent', compact('agent'));
    }

    /**
     * Update the specified agent in storage.
     */
 


    public function updateOld(Request $request, $id)
    {
        $agent = Agent::findOrFail($id);

        $validated = $request->validate([
            'agent_name' => 'required|string|max:255',
            // 'code_no' => 'required|string|unique:agents,code_no,' . $agent->id,
            'DOS' => 'required|date',
            // 'dob' => 'required|date',
            // 'age' => 'required|integer|min:18|max:65',
            // 'email' => 'required|email|unique:agents,email,' . $agent->id,
            'mobile' => 'required|string|max:20',
            // 'designation' => 'required|string|max:255',
            'sector' => 'required|string|max:255', // Changed from sector_id to sector
            'industry' => 'required|string|max:255', // Changed from industry_id to industry
            // 'qualfn' => 'required|string|max:255',
            // 'total_exp' => 'required|string|max:255',
            'placement_type' => 'required|string|max:255', // Changed from placement_type_id to placement_type
            // 'post_location_applied' => 'required|string|max:255',
            // 'current_stay' => 'required|string|max:255',
            // 'ctc' => 'required|string|max:255',
            // 'notice_period' => 'required|string|max:255',
            'user_type' => 'required|in:Domestic,International,Other',
            'sub_division' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB, nullable
            'passport_details' => 'nullable|string|max:255',
            'company_serial_number' => 'nullable|string|max:255',
        ]);

        // Handle resume upload if new file is provided
        $photoPath = $agent->resume; // Keep existing resume by default
        
        if ($request->hasFile('resume')) {
            // Delete old resume if exists
            if ($agent->resume && file_exists(public_path($agent->resume))) {
                unlink(public_path($agent->resume));
            }
            
            $file = $request->file('resume');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('resume/'), $filename);
            $photoPath = 'resume/'.$filename;
        } elseif ($request->has('remove_resume') && $request->remove_resume == '1') {
            // Remove resume if flag is set
            if ($agent->resume && file_exists(public_path($agent->resume))) {
                unlink(public_path($agent->resume));
            }
            $photoPath = null;
        }
        if ($request->type_business === 'Other') {
            $industry = \App\Models\Bussiness::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $type_business = $industry->name;
        } else {
            $type_business = $request->type_business;
        }

        // Prepare agent data for update
        $agentData = [
            'agent_name' => $validated['agent_name'] ?? null,
            'age' => $validated['age'] ?? null,
            'DOS' => $validated['DOS'] ?? null,
            'resume' => $photoPath ?? null,
            'code_no' => $validated['code_no'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'passport_details' => $validated['passport_details'] ?? $agent->passport_details,
            'qualfn' => $validated['qualfn'] ?? null,
            'sector' => $validated['sector'] ?? null,
            'industry' => $validated['industry'] ?? null,
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
            'company_serial_number' => $validated['company_serial_number'] ?? $agent->company_serial_number,
            'sub_division' => $validated['sub_division'] ?? $agent->sub_division,
            'field4' => $validated['field4'] ?? $agent->field4,
            // emp_code is not updated as it's auto-generated
        ];

        // Update the agent
        $agent->update($agentData);

        // Redirect with success message
        return redirect()->route('agents.show', $agent->id)
            ->with('success', 'Agent updated successfully!');
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'agent_company_name' => 'nullable|string|max:255',
            'type_business' => 'nullable|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'email_id' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'status' => 'nullable|in:1,2',
            'agent_business_profile_attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
            'user_type' => 'nullable|string|max:255',
            'mea_number' => 'nullable|string|max:255',
            'licence_number' => 'nullable|string|max:255',
            'agent_name_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:255',
            'alternate_number_two' => 'nullable|string|max:255',
            'email_id_two' => 'nullable|string|max:255',
            'sub_division' => 'nullable|string|max:255',
        ]);

        // Handle file removal
        if ($request->has('remove_file') && $request->remove_file == '1') {
            // Delete old file if exists
            if ($agent->agent_business_profile_attachment && file_exists(public_path($agent->agent_business_profile_attachment))) {
                unlink(public_path($agent->agent_business_profile_attachment));
            }
            $validated['agent_business_profile_attachment'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('agent_business_profile_attachment')) {
            // Delete old file if exists
            if ($agent->agent_business_profile_attachment && file_exists(public_path($agent->agent_business_profile_attachment))) {
                unlink(public_path($agent->agent_business_profile_attachment));
            }
            
            $file = $request->file('agent_business_profile_attachment');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('agent/'), $filename);
            $validated['agent_business_profile_attachment'] = 'agent/'.$filename;
        }
        if ($request->type_business === 'Other') {
            $industry = \App\Models\Bussiness::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $validated['type_business'] = $industry->name;
        } else {
            $validated['type_business'] = $request->type_business;
        }
        $agent->update($validated);

        return redirect()->route('agents.show', $agent->id)
            ->with('success', 'Agent updated successfully.');
    }

    /**
     * Remove the specified agent from storage.
     */
    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
        $agent->delete();

        return redirect()->route('agents.index')->with('success', 'Agent deleted successfully!');
    }

    public function sendToRecruiter(Request $request)
    {
        $request->validate([
            'recruiter_id' => 'required|exists:users,id',
            'agent_ids' => 'required',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);
        try {
            $recruiter = User::findOrFail($request->recruiter_id);
            $agentIds = explode(',', $request->agent_ids);
            $agents = Agent::whereIn('id', $agentIds)->get();

            // Check if any agents have resumes
            $agentsWithResumes = $agents->filter(function($agent) {
                return $agent->resume && Storage::exists('public/' . $agent->resume);
            });

            // Prepare email data
            $emailData = [
                'recruiter_name' => $recruiter->name,
                'subject' => $request->subject,
                'message_content' => $request->message,
                'agents' => $agents,
                'total_agents' => $agents->count(),
                'resumes_count' => $agentsWithResumes->count()
            ];

            // Send email with attachments
            Mail::send('emails.agents-to-recruiter', $emailData, function($message) use ($recruiter, $request, $agentsWithResumes) {
                $message->to($recruiter->email)
                        ->subject($request->subject);
                
                // Attach resumes
                foreach ($agentsWithResumes as $agent) {
                    $resumePath = storage_path('app/public/' . $agent->resume);
                    if (file_exists($resumePath)) {
                        $message->attach($resumePath, [
                            'as' => $agent->agent_name . '_Resume.' . pathinfo($resumePath, PATHINFO_EXTENSION),
                            'mime' => mime_content_type($resumePath)
                        ]);
                    }
                }
            });

            return redirect()->route('agents.index')
                ->with('success', 'Agents sent successfully to ' . $recruiter->email . 
                       ' with ' . $agentsWithResumes->count() . ' resume(s) attached');

        } catch (\Exception $e) {
            \Log::error('Failed to send agents: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to send agents: ' . $e->getMessage());
        }
    }
}
