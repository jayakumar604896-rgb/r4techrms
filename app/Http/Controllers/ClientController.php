<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
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


class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function indexOld()
    {
        $userType = Auth::user()->user_type;
        $clients = Client::when(
            in_array($userType, ['Domestic', 'International']),
            fn($query) => $query->where('user_type', $userType)
        )->orderByDesc('created_at')->get();
        return view('clients.index', compact('clients'));
    }
	public function index(Request $request)
    {
        $userType = Auth::user()->user_type;
        $type = $request->get('type', 'all');
        
        // Base query
        $query = Client::query();
        
        // Restrict based on logged-in user type
        if (in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', $userType);
        }
        
        // Apply type filter from URL (for admin/super admin)
        if ($type !== 'all' && !in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', ucfirst($type));
        }
        
        $clients = $query->orderByDesc('created_at')->get();
        
        // Get counts for stats
        $counts = [
            'all' => Client::count(),
            'Active' => Client::where('status', 1)->count(),
            'Inactive' => Client::where('status', 2)->count(),
            'International' => Client::where('user_type', 'International')->count(),
            'Domestic' => Client::where('user_type', 'Domestic')->count(),
        ];
        
        return view('clients.index', compact('clients', 'counts'));
    }
    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('clients.add-client');
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'user_type' => 'nullable|in:Domestic,International',
            'sub_division' => 'nullable|in:GuLF,SEA,EUROPE,OTHERS',
            'industry_id' => 'nullable|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mea_number' => 'nullable|string|max:255',
            'licence_number' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_person_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:255',
            'alternate_number_two' => 'nullable|string|max:255',
            'email_id_two' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'email_id' => 'nullable|email|max:255',
            'attachment_of_jd' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'attachment_of_other_docs' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'status' => 'nullable|in:1,2',
        ]);

        // Handle JD file upload
        if ($request->hasFile('attachment_of_jd')) {
            $file = $request->file('attachment_of_jd');
            $filename = time() . '_jd_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients/jd/'), $filename);
            $validated['attachment_of_jd'] = 'uploads/clients/jd/' . $filename;
        }

        // Handle other docs file upload
        if ($request->hasFile('attachment_of_other_docs')) {
            $file = $request->file('attachment_of_other_docs');
            $filename = time() . '_other_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients/other_docs/'), $filename);
            $validated['attachment_of_other_docs'] = 'uploads/clients/other_docs/' . $filename;
        }
        if ($request->industry_id === 'Others') {
            $industry = \App\Models\Industry::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $validated['industry_type'] = $industry->name;
        } else {
            $validated['industry_type'] = $request->industry_id;
        }
        $validated['created_by']    =   Auth::user()->id;
        // Create the client
        $client = Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client created successfully.');
    }
    /**
     * Display the specified client.
     */
    public function show($id)
    {
        $client      =   Client::findOrFail($id);
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        return view('clients/view-client', compact('client','placementtypes','industries','sectores'));
    }

    public function printClient($id)
    {
        $client      =   Client::findOrFail($id);
        $placementtypes =   PlacementType::pluck('name','id');
        $industries     =   Industry::pluck('name','id');
        $sectores       =   Sector::pluck('name','id');
        return view('clients/print', compact('client','placementtypes','industries','sectores'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit-client', compact('client'));
    }

    /**
     * Update the specified client in storage.
     */
 


    
    public function update(Request $request, Client $client)
    {
        // Validate the request
        $validated = $request->validate([
            'user_type' => 'nullable|in:Domestic,International',
            'sub_division' => 'nullable|in:GuLF,SEA,EUROPE,OTHERS',
            'industry_type' => 'nullable|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'gst_number' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:255',
            'desination' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'email_id' => 'nullable|email|max:255',
            'mea_number' => 'nullable|string|max:255',
            'licence_number' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'contact_person_two' => 'nullable|string|max:255',
            'desination_two' => 'nullable|string|max:255',
            'mobile_number_two' => 'nullable|string|max:255',
            'alternate_number_two' => 'nullable|string|max:255',
            'email_id_two' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'attachment_of_jd' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'attachment_of_other_docs' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'status' => 'nullable|in:1,2',
        ]);

        // Handle JD file removal/upload (using public folder approach)
        if ($request->has('remove_jd_file') && $request->remove_jd_file == '1') {
            if ($client->attachment_of_jd && file_exists(public_path($client->attachment_of_jd))) {
                unlink(public_path($client->attachment_of_jd));
            }
            $validated['attachment_of_jd'] = null;
        } elseif ($request->hasFile('attachment_of_jd')) {
            // Delete old JD file if exists
            if ($client->attachment_of_jd && file_exists(public_path($client->attachment_of_jd))) {
                unlink(public_path($client->attachment_of_jd));
            }
            
            $file = $request->file('attachment_of_jd');
            $filename = time() . '_jd_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients/jd/'), $filename);
            $validated['attachment_of_jd'] = 'uploads/clients/jd/' . $filename;
        } else {
            // Keep existing JD file
            unset($validated['attachment_of_jd']);
        }

        // Handle other docs file removal/upload (using public folder approach)
        if ($request->has('remove_other_docs_file') && $request->remove_other_docs_file == '1') {
            if ($client->attachment_of_other_docs && file_exists(public_path($client->attachment_of_other_docs))) {
                unlink(public_path($client->attachment_of_other_docs));
            }
            $validated['attachment_of_other_docs'] = null;
        } elseif ($request->hasFile('attachment_of_other_docs')) {
            // Delete old other docs file if exists
            if ($client->attachment_of_other_docs && file_exists(public_path($client->attachment_of_other_docs))) {
                unlink(public_path($client->attachment_of_other_docs));
            }
            
            $file = $request->file('attachment_of_other_docs');
            $filename = time() . '_other_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/clients/other_docs/'), $filename);
            $validated['attachment_of_other_docs'] = 'uploads/clients/other_docs/' . $filename;
        } else {
            // Keep existing other docs file
            unset($validated['attachment_of_other_docs']);
        }
        if ($request->industry_id === 'Others') {
            $industry = \App\Models\Industry::firstOrCreate([
                'name' => $request->other_industry
            ]);
            
            $validated['industry_type'] = $industry->name;
        } else {
            $validated['industry_type'] = $request->industry_id;
        }
        // Update the client
        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully!');
    }

    public function sendToRecruiter(Request $request)
    {
        $request->validate([
            'recruiter_id' => 'required|exists:users,id',
            'client_ids' => 'required',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);
        try {
            $recruiter = User::findOrFail($request->recruiter_id);
            $clientIds = explode(',', $request->client_ids);
            $clients = Client::whereIn('id', $clientIds)->get();

            // Check if any clients have resumes
            $clientsWithResumes = $clients->filter(function($client) {
                return $client->resume && Storage::exists('public/' . $client->resume);
            });

            // Prepare email data
            $emailData = [
                'recruiter_name' => $recruiter->name,
                'subject' => $request->subject,
                'message_content' => $request->message,
                'clients' => $clients,
                'total_clients' => $clients->count(),
                'resumes_count' => $clientsWithResumes->count()
            ];

            // Send email with attachments
            Mail::send('emails.clients-to-recruiter', $emailData, function($message) use ($recruiter, $request, $clientsWithResumes) {
                $message->to($recruiter->email)
                        ->subject($request->subject);
                
                // Attach resumes
                foreach ($clientsWithResumes as $client) {
                    $resumePath = storage_path('app/public/' . $client->resume);
                    if (file_exists($resumePath)) {
                        $message->attach($resumePath, [
                            'as' => $client->client_name . '_Resume.' . pathinfo($resumePath, PATHINFO_EXTENSION),
                            'mime' => mime_content_type($resumePath)
                        ]);
                    }
                }
            });

            return redirect()->route('clients.index')
                ->with('success', 'Clients sent successfully to ' . $recruiter->email . 
                       ' with ' . $clientsWithResumes->count() . ' resume(s) attached');

        } catch (\Exception $e) {
            \Log::error('Failed to send clients: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to send clients: ' . $e->getMessage());
        }
    }
}
