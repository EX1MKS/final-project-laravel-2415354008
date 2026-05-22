<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Status yang tersedia
    private const STATUSES = ['active', 'inactive'];

    /**
     * GET /api/customers
     * Ambil semua customer, bisa filter ?status=active|inactive
     */
    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $query = Customer::query();

        if ($status !== null) {
            if (!in_array($status, ['active', 'inactive'], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors'  => [
                        'status' => ['The selected status is invalid.'],
                    ],
                ], 422);
            }
            $query->where('status', $status === 'active');
        }

        $customers = $query->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Customers retrieved successfully',
            'data'    => $customers,
        ]);
    }

    /**
     * POST /api/customers
     * Tambah customer baru
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string'],
            'email'   => ['nullable', 'email', 'unique:customers,email'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status'  => ['nullable', 'boolean'],
        ]);

        $data['status'] = $data['status'] ?? true;

        $customer = Customer::query()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data'    => $customer,
        ], 201);
    }

    /**
     * GET /api/customers/{id}
     * Detail customer berdasarkan ID
     */
    public function show($customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors'  => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully',
            'data'    => $customer,
        ]);
    }

    /**
     * PUT/PATCH /api/customers/{id}
     * Update data customer
     */
    public function update(Request $request, $customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors'  => [],
            ], 404);
        }

        $data = $request->validate([
            'name'    => ['sometimes', 'string'],
            'email'   => ['nullable', 'email', 'unique:customers,email,' . $customer->id],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status'  => ['nullable', 'boolean'],
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data'    => $customer,
        ]);
    }

    /**
     * DELETE /api/customers/{id}
     * Hapus customer (tidak bisa jika punya subscription)
     */
    public function destroy($customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors'  => [],
            ], 404);
        }

        if ($customer->subscriptions()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Customer cannot be deleted because it has subscriptions',
                'errors'  => [],
            ], 422);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully',
            'data'    => null,
        ]);
    }

    /**
     * PATCH /api/customers/{id}/activate
     */
    public function activate($customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors'  => [],
            ], 404);
        }

        $customer->update(['status' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Customer activated successfully',
            'data'    => $customer,
        ]);
    }

    /**
     * PATCH /api/customers/{id}/deactivate
     */
    public function deactivate($customer): JsonResponse
    {
        $customer = Customer::query()->find($customer);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
                'errors'  => [],
            ], 404);
        }

        $customer->update(['status' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Customer deactivated successfully',
            'data'    => $customer,
        ]);
    }
}