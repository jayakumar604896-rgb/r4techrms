<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommunicationLog;
use App\Models\Brochure;
use App\Models\User;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;
use App\Models\CandidateFile;

class SendtoCandidatemailController extends Controller
{
    public function index()
    {
        $brochures = CommunicationLog::latest()->where('type', 'Mail Send to Candidate')->paginate(10);
        $brochurelists = Brochure::get();
        $users      =   User::pluck('name','id');
        $candidates = Candidate::get();
        return view('sendtocandidates.index', compact('brochures','brochurelists','users','candidates'));
    }

    public function create()
    {
        return view('sendtocandidates.create');
    }


    public function storeOld(Request $request)
    {
        $request->validate([
            'to_mail' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        try {
            $to = $request->to_mail;
            $subject = $request->subject;
            $content = $request->message;
            $attachments = [];
            
            // 1. Handle uploaded files
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    // Store file in storage
                    $path = $file->store('uploads/attachments', 'public');
                    
                    $attachments[] = [
                        'type' => 'uploaded',
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => 'storage/' . $path,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }
            
            // Check if any attachments exist
            if (empty($attachments)) {
                throw new \Exception('No attachments selected. Please select at least one file or brochure.');
            }

            // Send email with attachments
            Mail::send([], [], function($message) use ($to, $subject, $content, $attachments,$request) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($to)
                        ->subject($subject)
                        ->html($content);
                if($request->cc_mail != '') {
                    $message->cc($request->cc_mail);
                }
                // Add text version for better deliverability
                $message->text(strip_tags($content));

                foreach ($attachments as $attach) {
                    if ($attach['type'] === 'uploaded') {
                        // For uploaded files from storage
                        $message->attach(storage_path('app/public/' . str_replace('storage/', '', $attach['file_path'])), [
                            'as' => $attach['file_name'],
                            'mime' => $attach['mime_type'],
                        ]);
                    } else {
                        // For brochure files from public path
                        $message->attach(public_path($attach['file_path']), [
                            'as' => $attach['file_name'],
                            'mime' => $attach['mime_type'],
                        ]);
                    }
                }
            });

            // Find candidate
            $candidate = Candidate::where('email', $to)->first();
            
            // Store in communication log
            CommunicationLog::create([
                'type' => 'Mail Send to Candidate',
                'to_email' => $to,
                'cc_email' => $request->cc_mail ?? null,
                'subject' => $subject,
                'content' => $content,
                'candidate_id' => $candidate->id ?? null,
                'attachments' => json_encode($attachments),
                'attachments_count' => count($attachments),
                'created_by' => auth()->id(),
            ]);

            // Return success for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mail sent successfully with ' . count($attachments) . ' attachment(s)!'
                ]);
            }

            return redirect()->back()->with('success', 'Mail sent successfully with ' . count($attachments) . ' attachment(s)!');
            
        } catch (\Exception $e) {
            // Return error for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with('error', 'Error sending mail: ' . $e->getMessage());
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'to_mail' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'attachments' => 'sometimes|array',
            'attachments.*' => 'file|max:10240', // 10MB max per file
            'candidate_file_ids' => 'sometimes|array', // Allow candidate file IDs
        ]);

        try {
            $to = $request->to_mail;
            $subject = $request->subject;
            $content = $request->message;
            $attachments = [];

            // 1. Handle uploaded files (new attachments)
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('uploads/attachments', 'public');
                    $attachments[] = [
                        'type' => 'uploaded',
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path, // relative to storage/app/public
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }

            // 2. Handle selected candidate files (checkboxes)
            if ($request->has('candidate_file_ids') && is_array($request->candidate_file_ids)) {
                $candidateFiles = CandidateFile::whereIn('id', $request->candidate_file_ids)->get();
                foreach ($candidateFiles as $file) {
                    // Assuming file_path is stored relative to storage/app/public
                    $fullPath = storage_path('app/public/' . $file->file_path);
                    if (file_exists($fullPath)) {
                        $attachments[] = [
                            'type' => 'candidate',
                            'file_name' => $file->file_name,
                            'file_path' => $file->file_path, // relative path
                            'mime_type' => $file->mime_type ?? mime_content_type($fullPath),
                            'size' => $file->size ?? filesize($fullPath),
                        ];
                    }
                }
            }

            // 3. Check if any attachments exist (uploaded OR candidate files)
            if (empty($attachments)) {
                throw new \Exception('No attachments selected. Please select at least one file or brochure.');
            }

            // 4. Send email with attachments
            Mail::send([], [], function($message) use ($to, $subject, $content, $attachments, $request) {
                $message->from(config('mail.from.address'), config('mail.from.name'))
                        ->to($to)
                        ->subject($subject)
                        ->html($content);
                if ($request->cc_mail != '') {
                    $message->cc($request->cc_mail);
                }
                // Add text version for better deliverability
                $message->text(strip_tags($content));

                foreach ($attachments as $attach) {
                    if ($attach['type'] === 'uploaded' || $attach['type'] === 'candidate') {
                        // Both types are stored in storage/app/public
                        $message->attach(storage_path('app/public/' . $attach['file_path']), [
                            'as' => $attach['file_name'],
                            'mime' => $attach['mime_type'],
                        ]);
                    } else {
                        // For brochure files from public path (legacy)
                        $message->attach(public_path($attach['file_path']), [
                            'as' => $attach['file_name'],
                            'mime' => $attach['mime_type'],
                        ]);
                    }
                }
            });

            // 5. Log the communication
            $candidate = Candidate::where('email', $to)->first();
            CommunicationLog::create([
                'type' => 'Mail Send to Candidate',
                'to_email' => $to,
                'cc_email' => $request->cc_mail ?? null,
                'subject' => $subject,
                'content' => $content,
                'candidate_id' => $candidate->id ?? null,
                'attachments' => json_encode($attachments),
                'attachments_count' => count($attachments),
                'created_by' => auth()->id(),
            ]);

            // 6. Return appropriate response
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mail sent successfully with ' . count($attachments) . ' attachment(s)!'
                ]);
            }

            return redirect()->back()->with('success', 'Mail sent successfully with ' . count($attachments) . ' attachment(s)!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'Error sending mail: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $brochure   =   CommunicationLog::find($id);
        return view('sendtocandidates.edit', compact('brochure'));
    }

    public function update(Request $request, CommunicationLog $brochure)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|mimes:pdf|max:5120',
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => $request->is_active ? 1 : 0,
        ];

        // If new file uploaded
        if ($request->hasFile('file')) {


            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('brochures/files'), $filename);

            $data['file_path'] = 'brochures/files/'.$filename;
            $data['file_name'] = $filename;
        }

        $brochure->update($data);

        return redirect()->route('sendtocandidates.index')
            ->with('success', 'CommunicationLog updated successfully');
    }


    public function destroy(CommunicationLog $brochure)
    {
        Storage::disk('public')->delete($brochure->file_path);
        $brochure->delete();

        return redirect()->route('sendtocandidates.index')
            ->with('success', 'CommunicationLog deleted successfully.');
    }
}