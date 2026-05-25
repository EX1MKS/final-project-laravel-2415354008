@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

{{-- Top Row: Total Subscriptions & MRR --}}
<div class="stats-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
    <div class="stat-card indigo">
        <div class="stat-icon indigo">📋</div>
        <div>
            <div class="stat-value indigo">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Langganan Terdaftar</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">💰</div>
        <div>
            <div class="stat-value green">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
            <div class="stat-label">Monthly Recurring Revenue (MRR) - Aktif</div>
        </div>
    </div>
</div>

{{-- Mini Status Grid (Act as filter buttons) --}}
<div class="sub-status-grid">
    @foreach($statuses as $st)
        @php
            $isCurrent = ($status === $st);
            $colorClass = 'gray';
            if ($st === 'active') $colorClass = 'green';
            if ($st === 'trial') $colorClass = 'blue';
            if ($st === 'isolir') $colorClass = 'red';
            if ($st === 'dismantle') $colorClass = 'gray';
        @endphp
        <a href="{{ route('subscriptions.index', ['status' => $st, 'search' => $search]) }}" 
           class="sub-stat-mini" 
           style="display: block; border-color: {{ $isCurrent ? 'var(--primary)' : 'var(--border)' }}; 
                  background: {{ $isCurrent ? 'var(--light-primary)' : 'var(--white)' }};">
            <div class="value" style="color: {{ $isCurrent ? 'var(--primary)' : 'var(--black)' }};">{{ $stats[$st] }}</div>
            <div class="label">{{ $st }}</div>
        </a>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="toolbar">
    <div class="toolbar-left">
        @if($status)
            <a href="{{ route('subscriptions.index', ['search' => $search]) }}" class="btn btn-secondary btn-sm">
                ✕ Filter Status: {{ strtoupper($status) }}
            </a>
        @endif
        @if(request('customer_id'))
            <a href="{{ route('subscriptions.index', ['status' => $status, 'search' => $search, 'service_id' => request('service_id')]) }}" class="btn btn-secondary btn-sm">
                ✕ Filter Pelanggan
            </a>
        @endif
        @if(request('service_id'))
            <a href="{{ route('subscriptions.index', ['status' => $status, 'search' => $search, 'customer_id' => request('customer_id')]) }}" class="btn btn-secondary btn-sm">
                ✕ Filter Layanan
            </a>
        @endif
    </div>

    <div class="toolbar-right">
        <form action="{{ route('subscriptions.index') }}" method="GET" style="display: flex; gap: 8px; flex-wrap: wrap;">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            
            {{-- Customer Filter Dropdown --}}
            <select name="customer_id" class="form-control" style="width: 160px; padding: 6px 12px; font-size: 12px;" onchange="this.form.submit()">
                <option value="">Semua Pelanggan </option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>

            {{-- Service Filter Dropdown --}}
            <select name="service_id" class="form-control" style="width: 160px; padding: 6px 12px; font-size: 12px;" onchange="this.form.submit()">
                <option value="">Semua Layanan</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ request('service_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>

            <div class="search-input-wrap">
                <span class="search-icon">🔍</span>
                <input type="text" name="search" class="search-input" placeholder="Cari pelanggan/layanan..." value="{{ $search }}" style="width: 180px;">
            </div>

            @if($search || $status || request('customer_id') || request('service_id'))
                <a href="{{ route('subscriptions.index') }}" class="btn btn-ghost" title="Reset Semua Filter">Reset</a>
            @endif
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>

        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
            <span>➕</span> Tambah Subscription
        </a>
    </div>
</div>

{{-- Subscriptions Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Langganan Aktif & Riwayat</h3>
    </div>
    <div class="card-body">
        @if($subscriptions->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">📋</div>
                <h3>Subscription tidak ditemukan</h3>
                <p>Tidak ada data subscription yang cocok dengan kriteria filter Anda.</p>
                <a href="{{ route('subscriptions.create') }}" class="btn btn-primary btn-sm">Daftarkan Subscription Baru</a>
            </div>
        @else
            <div class="table-wrapper">
                <table class="erp-table">
                    <thead>
                        <tr>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Harga Bulanan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Status</th>
                            <th style="text-align: right; padding-right: 24px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $sub)
                            <tr>
                                <td>
                                    <strong>{{ $sub->customer->name }}</strong>
                                    <div class="id-value">{{ $sub->customer->customer_id }}</div>
                                </td>
                                <td>
                                    <strong>{{ $sub->service->name }}</strong>
                                </td>
                                <td>
                                    <span class="price-value">Rp {{ number_format($sub->service->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="id-value">{{ $sub->start_date ? $sub->start_date->format('d M Y') : '-' }}</span>
                                </td>
                                <td>
                                    <span class="id-value">{{ $sub->end_date ? $sub->end_date->format('d M Y') : 'Selamanya (Unlimited)' }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $sub->status }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td style="text-align: right; padding-right: 24px;">
                                    <div class="action-group" style="justify-content: flex-end;">
                                        <a href="{{ route('subscriptions.edit', $sub) }}" class="btn btn-secondary btn-sm">
                                            Edit
                                        </a>
                                        <a href="{{ route('subscriptions.delete', $sub) }}" class="btn btn-danger btn-sm btn-icon" title="Hapus">
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
