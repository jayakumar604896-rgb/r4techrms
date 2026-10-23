<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CommunicationLog;
use App\Models\Brochure;
use App\Models\User;
use Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mail;

class SendtoOthermailController extends Controller
{
    public function index(Request $request)
    {
 		$type = $request->get('type', 'all');
 		$userType = Auth::user()->user_type;
        $query = CommunicationLog::latest()->where('type', 'Mail Send to Others');
        $brochurelists = Brochure::get();
        $users      =   User::pluck('name','id');
 		if (in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', $userType);
        }
        
        // Apply type filter from URL (for admin/super admin)
        if ($type !== 'all' && !in_array($userType, ['Domestic', 'International'])) {
            $query->where('user_type', ucfirst($type));
        }
        
        $brochures = $query->paginate(10);
        
        // Get counts for stats
        $counts = [
            'all' => CommunicationLog::where('type', 'Mail Send to Others')->count(),
            'International' => CommunicationLog::where('user_type', 'International')->where('type', 'Mail Send to Others')->count(),
            'Domestic' => CommunicationLog::where('user_type', 'Domestic')->where('type', 'Mail Send to Others')->count(),
        ];
        return view('sendtoothers.index', compact('brochures','brochurelists','users','counts'));
    }

    public function create()
    {
        return view('sendtoothers.create');
    }

    public function storeOld(Request $request)
    {
        $request->validate([
            'to_mail' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'user_type' => 'required|string',
            'sub_division' => 'nullable|string',
        ]);

        try {
            $brochures = Brochure::where('is_active', 1)->get();

            $to = $request->to_mail;
            $subject = $request->subject;
            $content = $request->message;
            $attachments = [];
            foreach ($brochures as $brochure) {
                $filePath = public_path($brochure->file_path);
                if (file_exists($filePath)) {
                    $attachments[] = [
                        'file_name' => $brochure->file_name,
                        'file_path' => $brochure->file_path,
                    ];
                }
            }

            Mail::send([], [], function($message) use ($to, $subject, $content, $attachments, $request) {
                $message->to($to)
                        ->subject($subject)
                        ->html($content);
                
                if($request->cc_mail != '') {
                    $message->cc($request->cc_mail);
                }
                
                foreach ($attachments as $attach) {
                    $message->attach(public_path($attach['file_path']), [
                        'as' => $attach['file_name'],
                    ]);
                }
            });

            CommunicationLog::create([
                'type' => 'Mail Send to Others',
                'to_email' => $to,
                'cc_email' => $request->cc_mail ?? null,
                'sub_division' => $request->sub_division ?? null,
                'user_type' => $request->user_type ?? null,
                'subject' => $subject,
                'content' => $content,
                'attachments' => json_encode($attachments), 
                'created_by' => auth()->id(),
            ]);


            // Return success for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mail sent successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Mail sent successfully!');
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
    public function storeOld2(Request $request)
    {
        $request->validate([
            'to_mail' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
            'brochure_files' => 'required|array|min:1',
            'brochure_files.*' => 'string|exists:brochures,file_path',
        ]);

        try {
            $to = $request->to_mail;
            $subject = $request->subject;
            $content = $request->message;
            $attachments = [];
            
            // Get only selected brochures
            $selectedBrochurePaths = $request->brochure_files;
            $brochures = Brochure::whereIn('file_path', $selectedBrochurePaths)
                ->where('is_active', 1)
                ->get();
            
            foreach ($brochures as $brochure) {
                $filePath = public_path($brochure->file_path);
                if (file_exists($filePath)) {
                    $attachments[] = [
                        'file_name' => $brochure->file_name,
                        'file_path' => $brochure->file_path,
                    ];
                }
            }

            // Check if any attachments exist
            if (empty($attachments)) {
                throw new \Exception('No valid brochure files found. Please check the selected files.');
            }

            Mail::send([], [], function($message) use ($to, $subject, $content, $attachments) {
                $message->to($to)
                        ->cc("muthiah@paswigroup.com")
                        ->subject($subject)
                        ->html($content);

                foreach ($attachments as $attach) {
                    $message->attach(public_path($attach['file_path']), [
                        'as' => $attach['file_name'],
                    ]);
                }
            });

            CommunicationLog::create([
                'type' => 'Mail Send to Others',
                'to_email' => $to,
                'subject' => $subject,
                'content' => $content,
                'attachments' => json_encode($attachments), 
                'created_by' => auth()->id(),
            ]);

            // Return success for AJAX
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Mail sent successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Mail sent successfully!');
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
            'to_mail' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            'brochure_files' => 'required|array|min:1',
            'brochure_files.*' => 'string|exists:brochures,file_path',
        ]);

        try {
            $emails = array_map('trim', explode(',', $request->to_mail));
            
            $validEmails = [];
            $invalidEmails = [];
            
            foreach ($emails as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $validEmails[] = $email;
                } else {
                    $invalidEmails[] = $email;
                }
            }
            
            if (empty($validEmails)) {
                throw new \Exception('No valid email addresses found. Please check the email format.');
            }
            
            if (!empty($invalidEmails)) {
                $invalidEmailsStr = implode(', ', $invalidEmails);
                \Log::warning('Invalid email addresses provided: ' . $invalidEmailsStr);
                
                $warningMessage = "Some email addresses were invalid and ignored: $invalidEmailsStr";
            }
            
            $subject = $request->subject;
            $content = $request->message;
            $attachments = [];
            
            $selectedBrochurePaths = $request->brochure_files;
            $brochures = Brochure::whereIn('file_path', $selectedBrochurePaths)
                ->where('is_active', 1)
                ->get();
            
            foreach ($brochures as $brochure) {
                $filePath = public_path($brochure->file_path);
                if (file_exists($filePath)) {
                    $attachments[] = [
                        'file_name' => $brochure->file_name,
                        'file_path' => $brochure->file_path,
                    ];
                }
            }

            if (empty($attachments)) {
                throw new \Exception('No valid brochure files found. Please check the selected files.');
            }

            Mail::send([], [], function($message) use ($validEmails, $subject, $content, $attachments) {
                $message->from(
                    config('mail.from.address', 'noreply@example.com'),
                    config('mail.from.name', 'Marketing Team')
                );
                
                $message->to($validEmails)
                        ->cc("muthiah@paswigroup.com")
                        ->subject($subject)
                        ->html($content);
                
                $message->text(strip_tags($content));

                foreach ($attachments as $attach) {
                    $message->attach(public_path($attach['file_path']), [
                        'as' => $attach['file_name'],
                    ]);
                }
            });

            CommunicationLog::create([
                'type' => 'Mail Send to Others',
                'to_email' => implode(', ', $validEmails), // Store all emails as comma-separated
                'subject' => $subject,
                'content' => $content,
 				'sub_division' => $request->sub_division ?? null,
                'user_type' => $request->user_type ?? null,
                'attachments' => json_encode($attachments), 
                'created_by' => auth()->id(),
            ]);

            $successMessage = 'Mail sent successfully to ' . count($validEmails) . ' recipient(s)!';
            
            if (isset($warningMessage)) {
                $successMessage .= ' ' . $warningMessage;
            }

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $successMessage
                ]);
            }

            return redirect()->back()->with('success', $successMessage);
            
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
        return view('sendtoothers.edit', compact('brochure'));
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

        return redirect()->route('sendtoothers.index')
            ->with('success', 'CommunicationLog updated successfully');
    }


    public function destroy(CommunicationLog $brochure)
    {
        Storage::disk('public')->delete($brochure->file_path);
        $brochure->delete();

        return redirect()->route('sendtoothers.index')
            ->with('success', 'CommunicationLog deleted successfully.');
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
            
            // Get current user name
            $userName = auth()->user()->name ?? 'Marketing Team';
            
            // Send email with attachment
            \Mail::send([], [], function ($message) use ($file, $recipientEmail, $totalRecords, $userName) {
                $message->to($recipientEmail)
                        ->subject('Mail History Report - ' . date('d-m-Y'))
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
                                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                                    th { background: #004085; color: white; padding: 8px; text-align: left; }
                                    td { padding: 8px; border-bottom: 1px solid #ddd; }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="header">
                                        <h2 style="margin:0; color:white;">Mail History Report</h2>
                                    </div>
                                    <div class="content">
                                        <p>Dear Sir/Madam,</p>
                                        
                                        <p>Please find attached the mail history report PDF.</p>
                                        
                                        <div class="summary">
                                            <h3 style="margin-top:0;">Summary</h3>
                                            <p><strong>Date:</strong> ' . date('d-m-Y') . '</p>
                                            <p><strong>Time:</strong> ' . date('h:i A') . '</p>
                                        </div>
                                        
                                        <p>The attached PDF contains detailed information about all email marketing history including:</p>
                                        <ul>
                                            <li>Recipient email addresses</li>
                                            <li>Email subjects</li>
                                            <li>Sender information</li>
                                            <li>Date and time of sending</li>
                                        </ul>
                                        
                                        <p>
                                            Best regards,<br>
                                            <strong>' . $userName . '</strong><br>
                                            Marketing Team
                                        </p>
                                    </div>
                                    <div class="footer">
                                        <p style="margin:0;">This is an automated email from the Marketing System.</p>
                                        <p style="margin:5px 0 0;">&copy; ' . date('Y') . ' Marketing System. All rights reserved.</p>
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
            \Log::info("Mail history PDF sent to {$recipientEmail} with {$totalRecords} records");

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