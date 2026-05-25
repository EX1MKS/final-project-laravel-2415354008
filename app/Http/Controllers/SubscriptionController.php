<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    private const STATUSES = ['active', 'inactive', 'trial', 'isolir', 'dismantle'];

    /**
     * Tampilkan daftar semua subscription.
     */
    public function index(Request $request): View
    {
        $status     = $request->query('status');
        $search     = $request->query('search');
        $customerId = $request->query('customer_id');
        $serviceId  = $request->query('service_id');

        $query = Subscription::query()
            ->with(['customer', 'service']);

        if ($status && in_array($status, self::STATUSES, true)) {
            $query->where('status', $status);
        }

        if ($customerId) {
            $query->where('customer_id', $customerId);
        }

        if ($serviceId) {
            $query->where('service_id', $serviceId);
        }

        if ($search) {
            $query->whereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('service',  fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $subscriptions = $query->latest()->get();

        // Statistik per status
        $stats = collect(self::STATUSES)->mapWithKeys(fn($s) => [
            $s => Subscription::where('status', $s)->count(),
        ])->toArray();

        $stats['total'] = Subscription::count();

        // Total revenue dari yang aktif
        $stats['revenue'] = Subscription::where('subscriptions.status', 'active')
            ->join('services', 'subscriptions.service_id', '=', 'services.id')
            ->sum('services.price');

        // Untuk dropdown filter
        $customers = Customer::where('status', true)->orderBy('name')->get();
        $services  = Service::where('status', true)->orderBy('name')->get();

        return view('subscriptions.index', [
            'title'         => 'Subscriptions',
            'subtitle'      => 'Manajemen langganan pelanggan',
            'subscriptions' => $subscriptions,
            'stats'         => $stats,
            'statuses'      => self::STATUSES,
            'status'        => $status,
            'search'        => $search,
            'customers'     => $customers,
            'services'      => $services,
        ]);
    }

    /**
     * Tampilkan form buat subscription baru.
     */
    public function create(): View
    {
        $customers = Customer::where('status', true)->orderBy('name')->get();
        $services  = Service::where('status', true)->orderBy('name')->get();

        return view('subscriptions.create', [
            'title'     => 'Tambah Subscription',
            'subtitle'  => 'Daftarkan langganan baru',
            'customers' => $customers,
            'services'  => $services,
            'statuses'  => self::STATUSES,
        ]);
    }

    /**
     * Simpan subscription baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'      => ['nullable', Rule::in(self::STATUSES)],
        ]);

        $data['status'] = $data['status'] ?? 'trial';

        Subscription::create($data);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'Subscription baru berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit subscription.
     */
    public function edit(Subscription $subscription): View
    {
        $customers = Customer::orderBy('name')->get();
        $services  = Service::orderBy('name')->get();

        return view('subscriptions.edit', [
            'title'        => 'Edit Subscription',
            'subtitle'     => "Subscription #{$subscription->id}",
            'subscription' => $subscription->load(['customer', 'service']),
            'customers'    => $customers,
            'services'     => $services,
            'statuses'     => self::STATUSES,
        ]);
    }

    /**
     * Update subscription.
     */
    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'service_id'  => ['required', 'integer', 'exists:services,id'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'status'      => ['required', Rule::in(self::STATUSES)],
        ]);

        $subscription->update($data);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', "Subscription #{$subscription->id} berhasil diperbarui!");
    }

    /**
     * Tampilkan konfirmasi hapus.
     */
    public function delete(Subscription $subscription): View
    {
        return view('subscriptions.delete', [
            'title'        => 'Hapus Subscription',
            'subtitle'     => 'Konfirmasi penghapusan data',
            'subscription' => $subscription->load(['customer', 'service']),
        ]);
    }

    /**
     * Hapus subscription.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $id = $subscription->id;
        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', "Subscription #{$id} berhasil dihapus.");
    }
}