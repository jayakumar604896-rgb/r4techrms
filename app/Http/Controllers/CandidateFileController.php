<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateFileController extends Controller
{
    /**
     * Display a listing of the files.
     */
    public function index()
    {
        $files = CandidateFile::with('candidate')->latest()->paginate(15);
        return view('candidate_files.index', compact('files'));
    }

    /**
     * Show the form for creating a new file upload.
     */
    public function create()
    {
        $candidates = Candidate::select('id', 'candidate_name', 'mobile')->get();
        return view('candidate_files.create', compact('candidates'));
    }

    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'file_title'   => 'nullable|string|max:255',
            'file'         => 'required|file|mimes:pdf,doc,docx,jpg,png|max:5120', // max 5MB
        ]);

        $data = $request->only(['candidate_id', 'file_title']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('candidate_files', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        CandidateFile::create($data);

        return redirect()->route('candidate-files.index')
                         ->with('success', 'File uploaded successfully.');
    }

    /**
     * Display the specified file.
     */
    public function show(CandidateFile $candidateFile)
    {
        return view('candidate_files.show', compact('candidateFile'));
    }

    /**
     * Show the form for editing the file (only title maybe).
     */
    public function edit(CandidateFile $candidateFile)
    {
        $candidates = Candidate::select('id', 'candidate_name', 'mobile')->get();
        return view('candidate_files.edit', compact('candidateFile', 'candidates'));
    }

    /**
     * Update the file title (and optionally replace file).
     */
    public function update(Request $request, CandidateFile $candidateFile)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'file_title'   => 'nullable|string|max:255',
            'file'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        $data = $request->only(['candidate_id', 'file_title']);

        // If a new file is uploaded, delete the old one and store new
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($candidateFile->file_path);

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('candidate_files', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        $candidateFile->update($data);

        return redirect()->route('candidate-files.index')
                         ->with('success', 'File updated successfully.');
    }

    /**
     * Remove the file from storage and database.
     */
    public function destroy(CandidateFile $candidateFile)
    {
        // Delete physical file
        Storage::disk('public')->delete($candidateFile->file_path);

        $candidateFile->delete();

        return redirect()->route('candidate-files.index')
                         ->with('success', 'File deleted successfully.');
    }
}