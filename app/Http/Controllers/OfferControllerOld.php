<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class OfferController extends Controller
{
    /**
     * Display all offers.
     */
    public function index()
    {
        $offers = Offer::latest()->get();
        return view('offer.index', compact('offers'));
    }

    /**
     * Show add form.
     */
    public function create()
    {
        return view('offer.add-offer');
    }

    /**
     * Store new offer.
     */
   public function storeold(Request $request)
    {
        $request->validate([
            'user_type' => 'required',
            'employer_name' => 'required',
            'job_location' => 'required',
            'industry' => 'required',
            'designation_offered' => 'required',
            'employment_type' => 'required',
            'salary_offered' => 'required',
            'total_salary' => 'required',
            'food' => 'required',
            'accommodation' => 'required',
            'notice_period' => 'required',
        ]);

        Offer::create($request->all());

        return redirect()->route('offer.index')->with('success', 'Offer Added Successfully');
    }


    /**
     * View single offer.
     */
    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        return view('offer.view-offer', compact('offer'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        return view('offer.edit-offer', compact('offer'));
    }

    /**
     * Update offer.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employer_name' => 'required|string|max:255',
            'job_location' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'designation_offered' => 'required|string|max:255',
            'employment_type' => 'required|string',
            'salary_offered' => 'required|string|max:255',
            'total_salary' => 'required|string|max:255',
            'food' => 'required|string|max:255',
            'accommodation' => 'required|string|max:255',
            'notice_period' => 'required|string|max:255',
            'user_type' => 'required',
        ]);

        $offer = Offer::findOrFail($id);
        $offer->update($request->all());

        return redirect()->route('offer.index')
                         ->with('success', 'Offer details updated successfully!');
    }

    /**
     * Delete offer.
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        return redirect()->route('offer.index')
                         ->with('success', 'Offer deleted successfully!');
    }
}
