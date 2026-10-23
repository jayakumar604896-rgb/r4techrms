<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Brochure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrochureController extends Controller
{
    public function index()
    {
        $brochures = Brochure::latest()->paginate(10);
        return view('brochures.index', compact('brochures'));
    }

    public function create()
    {
        return view('brochures.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required',
            'is_active' => 'required|boolean',
        ]);

        $file = $request->file('file');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('brochures/files/'), $filename);
            $photoPath = 'brochures/files/'.$filename;
        }

        Brochure::create([
            'name'       => $request->name,
            'file_path' => $photoPath,
            'file_name' => $filename,
            'is_active' => $request->is_active ? 1 : 2,
        ]);

        return redirect()->route('newbrochures.index')
            ->with('success', 'Brochure uploaded successfully');
    }

    public function edit($id)
    {
        $brochure   =   Brochure::find($id);
        return view('brochures.edit', compact('brochure'));
    }

    public function update(Request $request, Brochure $brochure)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable',
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

        return redirect()->route('brochures.index')
            ->with('success', 'Brochure updated successfully');
    }


    public function destroy($id)
    {
        $brochure   =   Brochure::find($id);
        Storage::disk('public')->delete($brochure->file_path);
        $brochure->delete();

        return redirect()->route('newbrochures.index')->with('success', 'Brochure deleted successfully.');
    }
}