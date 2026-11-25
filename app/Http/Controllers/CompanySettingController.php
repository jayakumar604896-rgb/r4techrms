<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class CompanySettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = CompanySetting::latest()->get();
        return view('company-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company-settings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $setting = new CompanySetting();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Create directory if not exists
            $uploadPath = public_path('uploads/company-settings');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            
            // Move file to public/uploads/company-settings
            $image->move($uploadPath, $imageName);
            $setting->image = $imageName;
        }

        $setting->save();

        return redirect()->route('company-settings.index')
            ->with('success', 'Company setting created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySetting $companySetting)
    {
        return view('company-settings.show', compact('companySetting'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanySetting $companySetting)
    {
        return view('company-settings.edit', compact('companySetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CompanySetting $companySetting)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($companySetting->image && file_exists(public_path('uploads/company-settings/' . $companySetting->image))) {
                unlink(public_path('uploads/company-settings/' . $companySetting->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Create directory if not exists
            $uploadPath = public_path('uploads/company-settings');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            
            // Move file to public/uploads/company-settings
            $image->move($uploadPath, $imageName);
            $companySetting->image = $imageName;
        }

        $companySetting->save();

        return redirect()->route('company-settings.index')
            ->with('success', 'Company setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($companySetting)
    {
        if (!Auth::check() || strtolower(Auth::user()->role ?? '') !== 'admin') {
            abort(403, 'Unauthorized action. Only administrators can delete records.');
        }

        $setting = $companySetting instanceof CompanySetting ? $companySetting : CompanySetting::findOrFail($companySetting);

        // Delete image if exists
        if ($setting->image && file_exists(public_path('uploads/company-settings/' . $setting->image))) {
            @unlink(public_path('uploads/company-settings/' . $setting->image));
        }

        $setting->delete();

        return redirect()->route('company-settings.index')
            ->with('success', 'Company setting deleted successfully.');
    }
}