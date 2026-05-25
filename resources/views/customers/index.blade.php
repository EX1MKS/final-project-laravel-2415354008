@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

{{-- Stats Row --}}
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
        <div>
            <div class="stat-value blue">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><iconify-icon icon="mdi:check-circle"></iconify-icon></div>
        <div>
            <div class="stat-value green">{{ $stats['active'] }}</div>
            <div class="stat-label">Pelanggan Aktif</div>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><iconify-icon icon="mdi:close-circle"></iconify-icon></div>
        <div>
            <div class="stat-value red">{{ $stats['inactive'] }}</div>
            <div class="stat-label">Pelanggan Nonaktif</div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="toolbar-left">
        <div class="filter-tabs">
            <a href="{{ route('customers.index', ['search' => $search]) }}"
               class="filter-tab {{ $status === null ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('customers.index', ['status' => 'active', 'search' => $search]) }}"
               class="filter-tab {{ $status === 'active' ? 'active' : '' }}">
                Aktif
            </a>
            <a href="{{ route('customers.index', ['status' => 'inactive', 'search' => $search]) }}"
               class="filter-tab {{ $status === 'inactive' ? 'active' : '' }}">
                Nonaktif
            </a>
        </div>
    </div>

    <div class="toolbar-right">
        <form action="{{ route('customers.index') }}" method="GET" style="display: flex; gap: 8px;">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="search-input-wrap">
                <span class="search-icon"><iconify-icon icon="mdi:magnify" style="font-size:16px;"></iconify-icon></span>
                <input type="text" name="search" class="search-input" placeholder="Cari nama, email, ID..." value="{{ $search }}">
            </div>
            @if($search)
                <a href="{{ route('customers.index', ['status' => $status]) }}" class="btn btn-ghost" title="Hapus Pencarian"><iconify-icon icon="mdi:close" style="font-size:16px; vertical-align:-3px;"></iconify-icon></a>
            @endif
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <span><iconify-icon icon="mdi:plus" style="font-size:16px; vertical-align:-3px;"></iconify-icon></span> Tambah Customer
        </a>
    </div>
</div>

{{-- Customer Grid --}}
@if($customers->isEmpty())
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
            <h3>Pelanggan tidak ditemukan</h3>
            <p>Tidak ada data pelanggan yang cocok dengan kriteria pencarian Anda.</p>
            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm">Daftarkan Pelanggan Baru</a>
        </div>
    </div>
@else
    <div class="customer-grid">
        @foreach($customers as $cust)
            <div class="customer-card">
                <span class="badge badge-{{ $cust->status ? 'active' : 'inactive' }}" style="position: absolute; top: 22px; right: 22px;">
                    {{ $cust->status ? 'aktif' : 'nonaktif' }}
                </span>

                <div class="customer-avatar" style="background: {{ $cust->status ? 'linear-gradient(135deg, var(--primary), var(--tertiary))' : 'var(--gray)' }}">
                    {{ strtoupper(substr($cust->name, 0, 2)) }}
                </div>

                <h3 class="customer-card-name" title="{{ $cust->name }}">{{ $cust->name }}</h3>
                <div class="customer-card-id">{{ $cust->customer_id }}</div>

                <div class="customer-info-row">
                    <span class="icon"><iconify-icon icon="mdi:email" style="font-size:14px; vertical-align:-2px;"></iconify-icon></span>
                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $cust->email ?? '-' }}</span>
                </div>
                <div class="customer-info-row">
                    <span class="icon"><iconify-icon icon="mdi:phone" style="font-size:14px; vertical-align:-2px;"></iconify-icon></span>
                    <span>{{ $cust->phone ?? '-' }}</span>
                </div>
                <div class="customer-info-row">
                    <span class="icon"><iconify-icon icon="mdi:map-marker" style="font-size:14px; vertical-align:-2px;"></iconify-icon></span>
                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $cust->address ?? '-' }}</span>
                </div>
                <div class="customer-info-row" style="margin-top: 10px; font-weight: 600;">
                    <span class="icon"><iconify-icon icon="mdi:clipboard-text" style="font-size:14px; vertical-align:-2px;"></iconify-icon></span>
                    <span>{{ $cust->subscriptions_count }} Layanan Aktif</span>
                </div>

                <div class="customer-card-actions">
                    <a href="{{ route('customers.edit', $cust) }}" class="btn btn-secondary btn-sm" style="flex: 1; justify-content: center;">
                        Edit
                    </a>
                    
                    <form action="{{ route($cust->status ? 'customers.deactivate' : 'customers.activate', $cust) }}" method="POST" style="flex: 1; display: flex;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn {{ $cust->status ? 'btn-ghost' : 'btn-success' }} btn-sm" style="width: 100%; justify-content: center;">
                            {{ $cust->status ? 'Nonaktif' : 'Aktifkan' }}
                        </button>
                    </form>

                    <a href="{{ route('customers.delete', $cust) }}" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                        <iconify-icon icon="mdi:trash-can" style="font-size:14px; vertical-align:-2px;"></iconify-icon>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endcomponent
