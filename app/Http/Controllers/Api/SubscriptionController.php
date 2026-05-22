<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    // Status yang tersedia
    private const STATUSES = ['active', 'inactive', 'trial', 'isolir', 'dismantle'];

    /**
     * GET /api/subscriptions
     * Ambil semua subscription, bisa filter ?status=active|inactive|trial|isolir|dismantle
     * Bisa juga filter by ?customer_id=1 atau ?service_id=2
     */
    public function index(Request $request): JsonResponse
    {
        $query = Subscription::query()->with(['customer', 'service']);

        // Filter by status
        $status = $request->query('status');
        if ($status !== null) {
            if (!in_array($status, self::STATUSES, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => ['status' => ['The selected status is invalid.']],
                ], 422);
            }
            $query->where('status', $status);
        }

        // Filter by customer_id
        $customerId = $request->query('customer_id');
        if ($customerId !== null) {
            $query->where('customer_id', $customerId);
        }

        // Filter by service_id
        $serviceId = $request->query('service_id');
        if ($serviceId !== null) {
            $query->where('service_id', $serviceId);
        }

        $subscriptions = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Subscriptions retrieved successfully',
            'data'    => $subscriptions,
        ]);
    }

    /**
     * POST /api/subscriptions
     * Buat subscription baru
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'      => ['nullable', Rule::in(self::STATUSES)],
        ]);

        $data['status'] = $data['status'] ?? 'trial';

        $subscription = Subscription::query()->create($data);
        $subscription->load(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'data'    => $subscription,
        ], 201);
    }

    /**
     * GET /api/subscriptions/{id}
     * Detail subscription
     */
    public function show($subscription): JsonResponse
    {
        $subscription = Subscription::query()
            ->with(['customer', 'service'])
            ->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors'  => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscription retrieved successfully',
            'data'    => $subscription,
        ]);
    }

    /**
     * PUT/PATCH /api/subscriptions/{id}
     * Update subscription
     */
    public function update(Request $request, $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors'  => [],
            ], 404);
        }

        $data = $request->validate([
            'customer_id' => ['sometimes', 'integer', 'exists:customers,id'],
            'service_id'  => ['sometimes', 'integer', 'exists:services,id'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'      => ['nullable', Rule::in(self::STATUSES)],
        ]);

        $subscription->update($data);
        $subscription->load(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated successfully',
            'data'    => $subscription,
        ]);
    }

    /**
     * DELETE /api/subscriptions/{id}
     */
    public function destroy($subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors'  => [],
            ], 404);
        }

        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted successfully',
            'data'    => null,
        ]);
    }

    /**
     * PATCH /api/subscriptions/{id}/status
     * Ubah status subscription secara spesifik
     * Body: { "status": "active" }
     */
    public function changeStatus(Request $request, $subscription): JsonResponse
    {
        $subscription = Subscription::query()->find($subscription);

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found',
                'errors'  => [],
            ], 404);
        }

        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $subscription->update(['status' => $data['status']]);
        $subscription->load(['customer', 'service']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription status updated successfully',
            'data'    => $subscription,
        ]);
    }
}