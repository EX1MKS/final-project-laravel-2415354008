@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

{{-- Flash Alerts --}}
@if(session('success'))
    <div style="background:#f0fff4;border:1px solid #c6f6d5;border-left:4px solid #38a169;color:#276749;padding:12px 16px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:14px;">
        <span><iconify-icon icon="mdi:check-circle" style="font-size:16px; vertical-align:-3px;"></iconify-icon></span> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fff1f1;border:1px solid #ffcccc;border-left:4px solid #dc2626;color:#991b1b;padding:12px 16px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:14px;">
        <span><iconify-icon icon="mdi:cancel" style="font-size:16px; vertical-align:-3px;"></iconify-icon></span> {{ session('error') }}
    </div>
@endif
{{-- Top Row: Total Subscriptions & MRR --}}
<div class="stats-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 20px;">
    <div class="stat-card indigo">
        <div class="stat-icon indigo"><iconify-icon icon="mdi:clipboard-text"></iconify-icon></div>
        <div>
            <div class="stat-value indigo">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Langganan Terdaftar</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><iconify-icon icon="mdi:cash-multiple"></iconify-icon></div>
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
                <iconify-icon icon="mdi:close" style="font-size:14px; vertical-align:-2px;"></iconify-icon> Filter Status: {{ strtoupper($status) }}
            </a>
        @endif
        @if(request('customer_id'))
            <a href="{{ route('subscriptions.index', ['status' => $status, 'search' => $search, 'service_id' => request('service_id')]) }}" class="btn btn-secondary btn-sm">
                <iconify-icon icon="mdi:close" style="font-size:14px; vertical-align:-2px;"></iconify-icon> Filter Pelanggan
            </a>
        @endif
        @if(request('service_id'))
            <a href="{{ route('subscriptions.index', ['status' => $status, 'search' => $search, 'customer_id' => request('customer_id')]) }}" class="btn btn-secondary btn-sm">
                <iconify-icon icon="mdi:close" style="font-size:14px; vertical-align:-2px;"></iconify-icon> Filter Layanan
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
                <span class="search-icon"><iconify-icon icon="mdi:magnify" style="font-size:16px;"></iconify-icon></span>
                <input type="text" name="search" class="search-input" placeholder="Cari pelanggan/layanan..." value="{{ $search }}" style="width: 180px;">
            </div>

            @if($search || $status || request('customer_id') || request('service_id'))
                <a href="{{ route('subscriptions.index') }}" class="btn btn-ghost" title="Reset Semua Filter">Reset</a>
            @endif
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>

        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
            <span><iconify-icon icon="mdi:plus" style="font-size:16px; vertical-align:-3px;"></iconify-icon></span> Tambah Subscription
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
                <div class="empty-state-icon"><iconify-icon icon="mdi:clipboard-text"></iconify-icon></div>
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
                                    <div style="display: inline-flex; align-items: center; gap: 8px;">
                                        @if($sub->status === 'active')
                                            <iconify-icon icon="mdi:check-circle" style="color: var(--green); font-size: 18px;"></iconify-icon>
                                        @elseif($sub->status === 'inactive')
                                            <iconify-icon icon="mdi:close-circle" style="color: var(--red); font-size: 18px;"></iconify-icon>
                                        @elseif($sub->status === 'trial')
                                            <iconify-icon icon="mdi:clock-outline" style="color: var(--blue); font-size: 18px;"></iconify-icon>
                                        @elseif($sub->status === 'isolir')
                                            <iconify-icon icon="mdi:wifi-off" style="color: var(--red); font-size: 18px;"></iconify-icon>
                                        @elseif($sub->status === 'dismantle')
                                            <iconify-icon icon="mdi:lock" style="color: var(--gray); font-size: 18px;"></iconify-icon>
                                        @endif

                                        @if($sub->status === 'dismantle')
                                            {{-- Locked: tampilkan badge saja --}}
                                            <span class="badge badge-dismantle">
                                                DISMANTLE
                                            </span>
                                        @else
                                            {{-- Inline status dropdown --}}
                                            <form action="{{ route('subscriptions.updateStatus', $sub) }}" method="POST" style="margin:0;">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status"
                                                        onchange="this.form.submit()"
                                                        style="
                                                            padding: 5px 10px;
                                                            border-radius: 6px;
                                                            border: 1px solid var(--border, #e2e8f0);
                                                            font-size: 12px;
                                                            font-weight: 600;
                                                            cursor: pointer;
                                                            background: var(--white, #fff);
                                                            color: var(--black, #1a202c);
                                                        ">
                                                    @foreach($statuses as $st)
                                                        <option value="{{ $st }}" {{ $sub->status === $st ? 'selected' : '' }}>
                                                            {{ strtoupper($st) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif
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
