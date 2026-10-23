<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TrackingUserStatusController extends Controller
{
    public function heartbeat(Request $request)
    {
        if (Auth::check()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->update([
                'last_activity' => now(),
                'user_status' => 'active',
            ]);
        }

        return response()->noContent();
    }

    public function status($status)
    {
        if (Auth::check()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->update([
                'user_status' => $status,
            ]);
        }

        return response()->noContent();
    }
}
