<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Subscription\ActivateSubscriptionRequest;
use App\Http\Resources\UserSubscriptionResource;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptionService)
    {
    }

    public function show(): JsonResponse
    {
        $user = auth()->user();
        $active = $user->activeSubscription();

        return response()->json([
            'has_active_subscription' => $active !== null,
            'subscription' => $active ? new UserSubscriptionResource($active) : null,
            'plan' => config('subscription.single_plan'),
        ]);
    }

    public function activate(ActivateSubscriptionRequest $request): JsonResponse
    {
        $subscription = $this->subscriptionService->activateOrRenewYearly(
            $request->user(),
            array_filter($request->validated(), fn ($value) => $value !== null && $value !== '')
        );

        return response()->json([
            'message' => 'Subscription activated successfully.',
            'subscription' => new UserSubscriptionResource($subscription),
            'has_active_subscription' => true,
        ], 201);
    }

    public function cancel(): JsonResponse
    {
        $subscription = $this->subscriptionService->cancel(auth()->user());

        return response()->json([
            'message' => $subscription
                ? 'Subscription cancelled successfully.'
                : 'No active subscription found.',
            'subscription' => $subscription ? new UserSubscriptionResource($subscription) : null,
            'has_active_subscription' => false,
        ]);
    }
}
