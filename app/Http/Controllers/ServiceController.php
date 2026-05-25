<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Tampilkan daftar semua service.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Service::query()->withCount('subscriptions');

        if ($status && in_array($status, ['active', 'inactive'], true)) {
            $query->where('status', $status === 'active');
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $services = $query->latest()->get();

        // Statistik untuk ditampilkan di view
        $stats = [
            'total'    => Service::count(),
            'active'   => Service::where('status', true)->count(),
            'inactive' => Service::where('status', false)->count(),
        ];

        return view('services.index', [
            'title'    => 'Services',
            'subtitle' => 'Manajemen layanan digital',
            'services' => $services,
            'stats'    => $stats,
            'status'   => $status,
            'search'   => $search,
        ]);
    }

    /**
     * Tampilkan form buat service baru.
     */
    public function create(): View
    {
        return view('services.create', [
            'title'    => 'Tambah Service',
            'subtitle' => 'Buat layanan digital baru',
        ]);
    }

    /**
     * Simpan service baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        Service::create($data);

        return redirect()
            ->route('services.index')
            ->with('success', "Service \"{$data['name']}\" berhasil ditambahkan!");
    }

    /**
     * Tampilkan form edit service.
     */
    public function edit(Service $service): View
    {
        return view('services.edit', [
            'title'    => 'Edit Service',
            'subtitle' => "Mengedit: {$service->name}",
            'service'  => $service,
        ]);
    }

    /**
     * Update service di database.
     */
    public function update(Request $request, Service $service): RedirectResponse
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status'      => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status', true);

        $service->update($data);

        return redirect()
            ->route('services.index')
            ->with('success', "Service \"{$service->name}\" berhasil diperbarui!");
    }

    /**
     * Tampilkan halaman konfirmasi hapus.
     */
    public function delete(Service $service): View
    {
        return view('services.delete', [
            'title'    => 'Hapus Service',
            'subtitle' => 'Konfirmasi penghapusan data',
            'service'  => $service,
        ]);
    }

    /**
     * Hapus service dari database.
     */
    public function destroy(Service $service): RedirectResponse
    {
        if ($service->subscriptions()->exists()) {
            return redirect()
                ->route('services.index')
                ->with('error', "Service \"{$service->name}\" tidak dapat dihapus karena masih memiliki subscription aktif.");
        }

        $name = $service->name;
        $service->delete();

        return redirect()
            ->route('services.index')
            ->with('success', "Service \"{$name}\" berhasil dihapus.");
    }

    /**
     * Aktifkan service.
     */
    public function activate(Service $service): RedirectResponse
    {
        $service->update(['status' => true]);

        return back()->with('success', "Service \"{$service->name}\" berhasil diaktifkan.");
    }

    /**
     * Nonaktifkan service.
     */
    public function deactivate(Service $service): RedirectResponse
    {
        $service->update(['status' => false]);

        return back()->with('success', "Service \"{$service->name}\" berhasil dinonaktifkan.");
    }
}