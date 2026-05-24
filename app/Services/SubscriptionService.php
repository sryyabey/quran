<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    public function activateOrRenewYearly(User $user, array $meta = []): UserSubscription
    {
        $durationDays = (int) config('subscription.single_plan.duration_days', 365);
        $planCode = (string) config('subscription.single_plan.code', 'yearly');

        $current = $user->activeSubscription();
        $startsAt = $current?->ends_at && $current->ends_at->isFuture()
            ? $current->ends_at->copy()
            : Carbon::now();

        $endsAt = $startsAt->copy()->addDays($durationDays);

        return UserSubscription::query()->create([
            'user_id' => $user->id,
            'plan_code' => $planCode,
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'meta' => $meta,
        ]);
    }

    public function cancel(User $user): ?UserSubscription
    {
        $active = $user->activeSubscription();

        if (! $active) {
            return null;
        }

        $active->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return $active->fresh();
    }
}
