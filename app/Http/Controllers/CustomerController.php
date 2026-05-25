<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar semua customer.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Customer::query()->withCount('subscriptions');

        if ($status && in_array($status, ['active', 'inactive'], true)) {
            $query->where('status', $status === 'active');
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('customer_id', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->get();

        // Statistik untuk view
        $stats = [
            'total'         => Customer::count(),
            'active'        => Customer::where('status', true)->count(),
            'inactive'      => Customer::where('status', false)->count(),
        ];

        return view('customers.index', [
            'title'     => 'Customers',
            'subtitle'  => 'Manajemen data pelanggan',
            'customers' => $customers,
            'stats'     => $stats,
            'status'    => $status,
            'search'    => $search,
        ]);
    }

    /**
     * Tampilkan form tambah customer.
     */
    public function create(): View
    {
        return view('customers.create', [
            'title'    => 'Tambah Customer',
            'subtitle' => 'Daftarkan pelanggan baru',
        ]);
    }

    /**
     * Simpan customer baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'unique:customers,email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status'  => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        $customer = Customer::create($data);

        return redirect()
            ->route('customers.index')
            ->with('success', "Customer \"{$customer->name}\" ({$customer->customer_id}) berhasil didaftarkan!");
    }

    /**
     * Tampilkan form edit customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'title'    => 'Edit Customer',
            'subtitle' => "Mengedit: {$customer->name}",
            'customer' => $customer,
        ]);
    }

    /**
     * Update data customer.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'unique:customers,email,' . $customer->id, 'max:255'],
            'phone'   => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'status'  => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        $customer->update($data);

        return redirect()
            ->route('customers.index')
            ->with('success', "Data customer \"{$customer->name}\" berhasil diperbarui!");
    }

    /**
     * Tampilkan halaman konfirmasi hapus.
     */
    public function delete(Customer $customer): View
    {
        $subscriptionCount = $customer->subscriptions()->count();

        return view('customers.delete', [
            'title'             => 'Hapus Customer',
            'subtitle'          => 'Konfirmasi penghapusan data',
            'customer'          => $customer,
            'subscriptionCount' => $subscriptionCount,
        ]);
    }

    /**
     * Hapus customer dari database.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->subscriptions()->exists()) {
            return redirect()
                ->route('customers.index')
                ->with('error', "Customer \"{$customer->name}\" tidak dapat dihapus karena masih memiliki subscription.");
        }

        $name = $customer->name;
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', "Customer \"{$name}\" berhasil dihapus.");
    }

    /**
     * Aktifkan customer.
     */
    public function activate(Customer $customer): RedirectResponse
    {
        $customer->update(['status' => true]);
        return back()->with('success', "Customer \"{$customer->name}\" berhasil diaktifkan.");
    }

    /**
     * Nonaktifkan customer.
     */
    public function deactivate(Customer $customer): RedirectResponse
    {
        $customer->update(['status' => false]);
        return back()->with('success', "Customer \"{$customer->name}\" berhasil dinonaktifkan.");
    }
}