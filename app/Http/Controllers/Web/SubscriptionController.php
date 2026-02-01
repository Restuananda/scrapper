<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Billing\MidtransService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    public function __construct(
        protected MidtransService $midtrans
    ) {}

    public function plans()
    {
        $plans = Plan::active()->get()->map(fn ($plan) => [
            'id' => $plan->id,
            'name' => $plan->name,
            'display_name' => $plan->display_name,
            'price' => $plan->price,
            'price_formatted' => $plan->formatted_price,
            'price_yearly' => $plan->price_yearly,
            'yearly_savings' => $plan->yearly_savings_percent,
            'search_credits' => $plan->search_credits,
            'track_slots' => $plan->track_slots,
            'api_calls' => $plan->api_calls,
            'export_quota' => $plan->export_quota,
            'features' => $plan->features,
        ]);

        $currentPlan = auth()->user()->getPlan();

        return Inertia::render('Subscription/Plans', [
            'plans' => $plans,
            'currentPlan' => $currentPlan?->name,
        ]);
    }

    public function checkout(Request $request, Plan $plan)
    {
        $user = $request->user();

        // Check if already on this plan
        if ($user->isOnPlan($plan->name)) {
            return back()->with('error', 'Anda sudah berlangganan paket ini.');
        }

        // Create Midtrans transaction
        $transaction = $this->midtrans->createTransaction($user, $plan);

        return Inertia::render('Subscription/Checkout', [
            'plan' => [
                'id' => $plan->id,
                'name' => $plan->display_name,
                'price' => $plan->price,
                'price_formatted' => $plan->formatted_price,
                'features' => $plan->features,
            ],
            'snapToken' => $transaction['snap_token'],
            'orderId' => $transaction['order_id'],
        ]);
    }

    public function handleWebhook(Request $request)
    {
        // Verify Midtrans signature
        $notification = $this->midtrans->handleNotification($request->all());

        if (!$notification) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Process based on transaction status
        $orderId = $notification['order_id'];
        $status = $notification['transaction_status'];

        // Parse order ID to get user and plan
        // Format: SIP-{user_id}-{plan_id}-{timestamp}
        preg_match('/SIP-(\d+)-(\d+)-/', $orderId, $matches);
        
        if (count($matches) !== 3) {
            return response()->json(['error' => 'Invalid order ID'], 400);
        }

        $userId = $matches[1];
        $planId = $matches[2];

        if (in_array($status, ['capture', 'settlement'])) {
            // Payment successful - activate subscription
            $this->activateSubscription($userId, $planId, $orderId);
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            // Payment failed
            // Log or handle failed payment
        }

        return response()->json(['status' => 'ok']);
    }

    protected function activateSubscription(int $userId, int $planId, string $orderId): void
    {
        $user = \App\Models\User::findOrFail($userId);
        $plan = Plan::findOrFail($planId);

        // Cancel existing subscription
        $user->subscriptions()->where('status', 'active')->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        // Create new subscription
        $subscription = $user->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'gateway' => 'midtrans',
            'gateway_subscription_id' => $orderId,
        ]);

        // Add credits
        $user->addCredits('search', $plan->search_credits, 'Subscription activated', 'subscription', $subscription->id);
        $user->addCredits('track', $plan->track_slots, 'Subscription activated', 'subscription', $subscription->id);
        $user->addCredits('api', $plan->api_calls, 'Subscription activated', 'subscription', $subscription->id);
        $user->addCredits('export', $plan->export_quota, 'Subscription activated', 'subscription', $subscription->id);
    }

    public function cancel(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription;

        if (!$subscription) {
            return back()->with('error', 'Anda tidak memiliki langganan aktif.');
        }

        $subscription->cancel();

        return back()->with('success', 'Langganan dibatalkan. Anda masih bisa menggunakan fitur hingga akhir periode.');
    }

    public function billing(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription;
        $plan = $user->getPlan();

        $transactions = $user->creditTransactions()
            ->where('reference_type', 'subscription')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($t) => [
                'date' => $t->created_at->format('d M Y'),
                'description' => $t->description,
                'amount' => $t->amount,
            ]);

        return Inertia::render('Settings/Billing', [
            'subscription' => $subscription ? [
                'plan' => $plan->display_name,
                'status' => $subscription->status,
                'current_period_end' => $subscription->current_period_end?->format('d M Y'),
                'is_cancelled' => $subscription->isCancelled(),
            ] : null,
            'credits' => $user->getCreditsArray(),
            'transactions' => $transactions,
        ]);
    }
}
