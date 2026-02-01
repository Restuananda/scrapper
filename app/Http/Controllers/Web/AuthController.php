<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => 'required|accepted',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Give starter credits
        $this->giveStarterCredits($user);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // ==========================================
    // GOOGLE OAUTH
    // ==========================================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google.');
        }

        // Find or create user
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            // Update google_id if not set
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }
        } else {
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]);

            // Give starter credits
            $this->giveStarterCredits($user);
        }

        Auth::login($user, true);

        return redirect('/dashboard');
    }

    // ==========================================
    // PASSWORD RESET
    // ==========================================

    public function showForgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // TODO: Implement password reset
        
        return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
    }

    // ==========================================
    // HELPERS
    // ==========================================

    protected function giveStarterCredits(User $user): void
    {
        // Get starter plan or default credits
        $starterPlan = Plan::where('name', 'starter')->first();

        if ($starterPlan) {
            $user->update([
                'search_credits' => $starterPlan->search_credits,
                'track_slots' => $starterPlan->track_slots,
                'api_calls' => $starterPlan->api_calls,
                'export_quota' => $starterPlan->export_quota,
            ]);

            // Create trial subscription
            $user->subscriptions()->create([
                'plan_id' => $starterPlan->id,
                'status' => 'trialing',
                'trial_ends_at' => now()->addDays(14),
                'current_period_start' => now(),
                'current_period_end' => now()->addDays(14),
            ]);
        } else {
            // Default starter credits
            $user->update([
                'search_credits' => 500,
                'track_slots' => 20,
                'api_calls' => 0,
                'export_quota' => 3,
            ]);
        }
    }
}
