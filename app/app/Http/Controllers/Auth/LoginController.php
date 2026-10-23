<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;


class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Redirect if already logged in
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Validate request
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:100',
        ]);

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            // Check if user exists and password matches
            if (!$user || !Hash::check($request->password, $user->password)) {
                return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
            }

            // Generate OTP (secure)
            $otp = random_int(100000, 999999);
            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            // Send OTP via mail
            Mail::to($user->email)->queue(new OtpMail($otp, $user));

            // Temporarily store user ID in session
            session(['otp_user_id' => $user->id]);

            // Handle Remember Me (username only recommended)
            if ($request->has('remember')) {
                Cookie::queue('username', $request->email, 60 * 24 * 30); // 30 days
                Cookie::queue('password', $request->password, 60 * 24 * 30); // 30 days
                Cookie::queue('remember', true, 60 * 24 * 30);
            } else {
                Cookie::queue(Cookie::forget('username'));
                Cookie::queue(Cookie::forget('remember'));
                Cookie::queue(Cookie::forget('password'));
            }

            return redirect()->route('otp-form');

        } catch (\Exception $e) {
            // Handle any exception
            return back()->withErrors(['error' => 'Something went wrong. Please try again later.'])->withInput();
        }
    }

    public function otpForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        $session = session('otp_user_id');
        if (!$session) {
            return redirect()->route('login')->withErrors('Session expired. Please login again.');
        }
        return view('otp-form');
    }

    public function dashboard() {
        $adminUsers = User::where('role', 'admin')->get(['id', 'name', 'user_status']);
        $interUsers = User::where('role', 'international')->get(['id', 'name', 'user_status']);
        $domesticUsers = User::where('role', 'domestic')->get(['id', 'name', 'user_status']);
        return view('dashboard', compact('adminUsers','interUsers','domesticUsers'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors('Session expired. Please login again.');
        }

        if ($user->otp === $request->otp && Carbon::now()->lte($user->otp_expires_at)) {
            // OTP correct and valid
            $user->update(['otp' => null, 'otp_expires_at' => null,'last_activity' => now(),
                'user_status' => 'active']);

            Auth::login($user);
            session()->forget('otp_user_id');

            return redirect()->intended('/dashboard')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function logout()
    {
        // Logout the user
         if (Auth::check()) {
            $user = User::where('id', Auth::user()->id)->first();
            $user->update([
                'user_status' => 'offline',
            ]);
        }
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have logged out successfully.');
    }
}
