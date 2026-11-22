<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\SubscribeRequest;
use App\Http\Requests\Staff\VerifyPaymentRequest;
use App\Http\Resources\Staff\SubscriptionPlanResource;
use App\Http\Resources\Staff\SubscriptionResource;
use App\Models\SubscriptionPlan;
use App\Services\Billing\PharmacySubscriptionService;
use Illuminate\Http\JsonResponse;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionController extends Controller
{
    public function __construct(private readonly PharmacySubscriptionService $subscriptionService) {}

    public function plans(): AnonymousResourceCollection
    {
        return SubscriptionPlanResource::collection(
            SubscriptionPlan::where('is_active', true)->get()
        );
    }

    public function current(Request $request): JsonResponse
    {
        $subscription = $request->user()->pharmacy->subscription;

        return response()->json($subscription ? new SubscriptionResource($subscription->load('plan')) : null);
    }

    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $plan = SubscriptionPlan::findOrFail($request->integer('subscription_plan_id'));
        $durationMonths = $request->integer('duration_months', 1);

        $result = $this->subscriptionService->initiate($request->user()->pharmacy, $plan, $durationMonths);

        return response()->json($result);
    }

    public function verifyPayment(VerifyPaymentRequest $request): JsonResponse
    {
        $result = $this->subscriptionService->verifyPayment(
            $request->user()->pharmacy,
            $request->string('reference')->toString()
        );

        return response()->json($result);
    }

    public function paymentHistory(Request $request): JsonResponse
    {
        $payments = SubscriptionPayment::where('pharmacy_id', $request->user()->pharmacy_id)
            ->with('plan')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $payments->items(),
            'meta' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'total' => $payments->total(),
            ],
        ]);
    }
}
