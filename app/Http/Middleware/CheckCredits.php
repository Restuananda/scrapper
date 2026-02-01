<?php
// =====================================================
// app/Http/Middleware/CheckCredits.php
// =====================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCredits
{
    public function handle(Request $request, Closure $next, string $type = 'search', int $amount = 1): Response
    {
        $user = $request->user();

        if (!$user->hasCredits($type, $amount)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'insufficient_credits',
                    'message' => "Tidak cukup kredit {$type}. Upgrade paket Anda.",
                    'required' => $amount,
                    'available' => $user->{$type . '_credits'} ?? 0,
                ], 402);
            }

            return back()->with('error', "Kredit {$type} tidak mencukupi. Upgrade paket Anda.");
        }

        return $next($request);
    }
}
