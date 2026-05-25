@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

{{-- Stats Row --}}
<div class="stats-grid">
    <div class="stat-card cyan">
        <div class="stat-icon cyan">⚙️</div>
        <div>
            <div class="stat-value cyan">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Layanan</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">✅</div>
        <div>
            <div class="stat-value green">{{ $stats['active'] }}</div>
            <div class="stat-label">Layanan Aktif</div>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red">❌</div>
        <div>
            <div class="stat-value red">{{ $stats['inactive'] }}</div>
            <div class="stat-label">Layanan Nonaktif</div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="toolbar-left">
        <div class="filter-tabs">
            <a href="{{ route('services.index', ['search' => $search]) }}"
               class="filter-tab {{ $status === null ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('services.index', ['status' => 'active', 'search' => $search]) }}"
               class="filter-tab {{ $status === 'active' ? 'active' : '' }}">
                Aktif
            </a>
            <a href="{{ route('services.index', ['status' => 'inactive', 'search' => $search]) }}"
               class="filter-tab {{ $status === 'inactive' ? 'active' : '' }}">
                Nonaktif
            </a>
        </div>
    </div>

    <div class="toolbar-right">
        <form action="{{ route('services.index') }}" method="GET" style="display: flex; gap: 8px;">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="search-input-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" class="search-input" placeholder="Cari nama layanan..." value="{{ $search }}">
            </div>
            @if($search)
                <a href="{{ route('services.index', ['status' => $status]) }}" class="btn btn-ghost" title="Hapus Pencarian">✕</a>
            @endif
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
        <a href="{{ route('services.create') }}" class="btn btn-primary">
            <span>➕</span> Tambah Service
        </a>
    </div>
</div>

{{-- Services Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Layanan Digital</h3>
    </div>
    <div class="card-body">
        @if($services->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">⚙️</div>
                <h3>Layanan tidak ditemukan</h3>
                <p>Tidak ada data layanan digital yang cocok dengan kriteria pencarian Anda.</p>
                <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm">Buat Layanan Baru</a>
            </div>
        @else
            <div class="table-wrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Nama Layanan</th>
                            <th>Harga Bulanan</th>
                            <th>Deskripsi</th>
                            <th>Total Pelanggan</th>
                            <th>Status</th>
                            <th style="text-align: right; padding-right: 24px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $svc)
                            <tr>
                                <td>
                                    <strong>{{ $svc->name }}</strong>
                                </td>
                                <td>
                                    <span class="price-value">Rp {{ number_format($svc->price, 0, ',', '.') }}</span>
                                </td>
                                <td style="max-width: 320px; font-size: 12.5px; color: var(--dark-gray);">
                                    {{ Str::limit($svc->description, 80) ?? '-' }}
                                </td>
                                <td>
                                    <strong>{{ $svc->subscriptions_count }}</strong> Pelanggan
                                </td>
                                <td>
                                    <span class="badge badge-{{ $svc->status ? 'active' : 'inactive' }}">
                                        {{ $svc->status ? 'aktif' : 'nonaktif' }}
                                    </span>
                                </td>
                                <td style="text-align: right; padding-right: 24px;">
                                    <div class="action-group" style="justify-content: flex-end;">
                                        <a href="{{ route('services.edit', $svc) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route($svc->status ? 'services.deactivate' : 'services.activate', $svc) }}" method="POST" style="display: inline-flex;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $svc->status ? 'btn-ghost' : 'btn-success' }} btn-sm" style="min-width: 90px; justify-content: center;">
                                                {{ $svc->status ? 'Nonaktif' : 'Aktifkan' }}
                                            </button>
                                        </form>

                                        <a href="{{ route('services.delete', $svc) }}" class="btn btn-danger btn-sm btn-icon" title="Hapus">
                                            🗑️
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endcomponent
