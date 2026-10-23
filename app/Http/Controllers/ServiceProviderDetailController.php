<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProviderDetail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\PlacementType;
use App\Models\Industry;
use App\Models\Sector;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class ServiceProviderDetailController extends Controller
{
    /**
     * Display a listing of the serviceproviderdetails.
     */
    public function index(Request $request)
    {
		$type = $request->get('type', 'all');
        $userType = Auth::user()->user_type;
        $query = ServiceProviderDetail::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        );
		if (in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', $userType);
        }
        
        if ($type !== 'all' && !in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', ucfirst($type));
        }
		$serviceproviderdetails=$query->orderByDesc('created_at')->get();
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
 		$counts = [
            'International' => ServiceProviderDetail::where('user_type', 'International')->count(),
            'Domestic' => ServiceProviderDetail::where('user_type', 'Domestic')->count(),
        ];
        return view('serviceproviderdetails.index', compact('serviceproviderdetails','placementtypes','industries','sectores','counts'));
    }

    /**
     * Show the form for creating a new serviceproviderdetail.
     */
    public function create()
    {
        return view('serviceproviderdetails.add-service');
    }

    /**
     * Store a newly created serviceproviderdetail in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_company_name' => 'nullable|string|max:255',
            'user_type' => 'nullable|string|max:255',
            'sub_division' => 'nullable|string|max:255',
            'type_business' => 'nullable|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'alternate_number' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'email_id' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50|regex:/^[A-Z0-9]{15}$/',
            'status' => 'nullable|in:1,2',
            'agent_name_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'alternate_number_two' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'email_id_two' => 'nullable|email|max:255',
            'udhayam_number' => 'nullable|string',
            'licence_number' => 'nullable|string',
            'mcse_number' => 'nullable|string',

        ], [
            'mobile_number.regex' => 'Mobile number must be exactly 10 digits.',
            'alternate_number.regex' => 'Alternate number must be exactly 10 digits.',
            'gst_number.regex' => 'GST number must be exactly 15 alphanumeric characters.',
            'status.in' => 'Status must be either Active (1) or Inactive (2).',
        ]);
        if ($request->type_business === 'Other') {
            $industry = \App\Models\Bussiness::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $validated['type_business'] = $industry->name;
        } else {
            $validated['type_business'] = $request->type_business;
        }
        ServiceProviderDetail::create($validated);

        return redirect()->route('service-provider-details.index')
            ->with('success', 'Service provider created successfully.');
    }
    /**
     * Display the specified agent.
     */
    public function show($id)
    {
        $serviceProvider      =   ServiceProviderDetail::findOrFail($id);
        return view('serviceproviderdetails/view-service', compact('serviceProvider'));
    }

    public function printProvider($id)
    {
        $serviceProvider      =   ServiceProviderDetail::findOrFail($id);
        return view('serviceproviderdetails/print', compact('serviceProvider'));
    }

    /**
     * Show the form for editing the specified serviceproviderdetail.
     */
    public function edit($id)
    {
        $serviceProvider = ServiceProviderDetail::findOrFail($id);
        return view('serviceproviderdetails.edit-service', compact('serviceProvider'));
    }

    /**
     * Update the specified serviceproviderdetail in storage.
     */
 


    
    public function update(Request $request, ServiceProviderDetail $serviceProviderDetail)
    {
        // All fields are optional, validate only format if provided
        $validated = $request->validate([
            'service_company_name' => 'nullable|string|max:255',
            'user_type' => 'nullable|string|max:255',
            'sub_division' => 'nullable|string|max:255',
            'type_business' => 'nullable|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'alternate_number' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'email_id' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50|regex:/^[A-Z0-9]{15}$/',
            'status' => 'nullable|in:1,2',
            'agent_name_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'alternate_number_two' => 'nullable|string|max:20|regex:/^[0-9]{10}$/',
            'email_id_two' => 'nullable|email|max:255',
            'udhayam_number' => 'nullable|string',
            'licence_number' => 'nullable|string',
            'mcse_number' => 'nullable|string',

        ], [
            'mobile_number.regex' => 'Mobile number must be exactly 10 digits.',
            'alternate_number.regex' => 'Alternate number must be exactly 10 digits.',
            'gst_number.regex' => 'GST number must be exactly 15 alphanumeric characters.',
            'status.in' => 'Status must be either Active (1) or Inactive (2).',
        ]);

        // Update the service provider
        if ($request->type_business === 'Other') {
            $industry = \App\Models\Bussiness::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $validated['type_business'] = $industry->name;
        } else {
            $validated['type_business'] = $request->type_business;
        }
        $serviceProviderDetail->update($validated);

        return redirect()->route('service-provider-details.show', $serviceProviderDetail->id)
            ->with('success', 'Service provider updated successfully.');
    }

    /**
     * Remove the specified agent from storage.
     */
    public function destroy($id)
    {
        $serviceproviderdetail = ServiceProviderDetail::findOrFail($id);
        $serviceproviderdetail->delete();

        return redirect()->route('service-provider-details.index')->with('success', 'ServiceProviderDetail deleted successfully!');
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
            $agents = ServiceProviderDetail::whereIn('id', $agentIds)->get();

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

            return redirect()->route('service-provider-details.index')
                ->with('success', 'ServiceProviderDetails sent successfully to ' . $recruiter->email . 
                       ' with ' . $agentsWithResumes->count() . ' resume(s) attached');

        } catch (\Exception $e) {
            \Log::error('Failed to send agents: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to send agents: ' . $e->getMessage());
        }
    }
}
