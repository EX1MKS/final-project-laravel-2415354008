@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="stats-grid">
    {{-- Card Customer --}}
    <div class="stat-card blue">
        <div class="stat-icon blue"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
        <div>
            <div class="stat-value blue">{{ $stats['customers']['total'] }}</div>
            <div class="stat-label">Customers ({{ $stats['customers']['active'] }} Aktif)</div>
        </div>
    </div>

    {{-- Card Service --}}
    <div class="stat-card cyan">
        <div class="stat-icon cyan"><iconify-icon icon="mdi:cog"></iconify-icon></div>
        <div>
            <div class="stat-value cyan">{{ $stats['services']['total'] }}</div>
            <div class="stat-label">Services ({{ $stats['services']['active'] }} Aktif)</div>
        </div>
    </div>

    {{-- Card Subscription --}}
    <div class="stat-card indigo">
        <div class="stat-icon indigo"><iconify-icon icon="mdi:clipboard-text"></iconify-icon></div>
        <div>
            <div class="stat-value indigo">{{ $stats['subscriptions']['total'] }}</div>
            <div class="stat-label">Subscriptions ({{ $stats['subscriptions']['active'] }} Aktif)</div>
        </div>
    </div>

    {{-- Card Revenue --}}
    <div class="stat-card green">
    <div class="stat-icon green"><iconify-icon icon="mdi:cash-multiple"></iconify-icon></div>
    <div>
        <div class="stat-value green" style="font-size: 18px;">
            Rp {{ number_format($stats['subscriptions']['revenue'], 0, ',', '.') }}
        </div>
        <div class="stat-label" style="font-size: 12px;">
            Monthly Revenue
        </div>
    </div>
</div>
</div>

<div class="form-row" style="grid-template-columns: 2fr 1fr; gap: 24px; margin-top: 8px;">
    {{-- Latest Subscriptions --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Langganan Terbaru</h3>
            <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body">
            @if($latestSubscriptions->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon"><iconify-icon icon="mdi:clipboard-text"></iconify-icon></div>
                    <h3>Belum ada data</h3>
                    <p>Tidak ada data subscription yang terdaftar.</p>
                </div>
            @else
                <div class="table-wrapper">
                    <table class="erp-table">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Mulai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestSubscriptions as $sub)
                                <tr>
                                    <td>
                                        <strong>{{ $sub->customer->name }}</strong>
                                        <div class="id-value">{{ $sub->customer->customer_id }}</div>
                                    </td>
                                    <td>
                                        <strong>{{ $sub->service->name }}</strong>
                                        <div class="price-value">Rp {{ number_format($sub->service->price, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $sub->status }}" style="display: inline-flex; align-items: center; gap: 6px;">
                                            @if($sub->status === 'active')
                                                <iconify-icon icon="mdi:check-circle" style="font-size: 13px;"></iconify-icon>
                                            @elseif($sub->status === 'inactive')
                                                <iconify-icon icon="mdi:close-circle" style="font-size: 13px;"></iconify-icon>
                                            @elseif($sub->status === 'trial')
                                                <iconify-icon icon="mdi:clock-outline" style="font-size: 13px;"></iconify-icon>
                                            @elseif($sub->status === 'isolir')
                                                <iconify-icon icon="mdi:wifi-off" style="font-size: 13px;"></iconify-icon>
                                            @elseif($sub->status === 'dismantle')
                                                <iconify-icon icon="mdi:lock" style="font-size: 13px;"></iconify-icon>
                                            @endif
                                            {{ strtoupper($sub->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="id-value">{{ $sub->start_date ? $sub->start_date->format('d M Y') : '-' }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Latest Customers --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pelanggan Baru</h3>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding: 16px;">
            @if($latestCustomers->isEmpty())
                <div class="empty-state" style="padding: 20px;">
                    <div class="empty-state-icon" style="font-size: 32px;"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
                    <h3>Belum ada data</h3>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @foreach($latestCustomers as $cust)
                        <div style="display: flex; align-items: center; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid var(--light-gray);">
                            <div class="customer-avatar" style="margin-bottom: 0; width: 36px; height: 36px; font-size: 14px; background: linear-gradient(135deg, var(--primary), var(--tertiary));">
                                {{ strtoupper(substr($cust->name, 0, 2)) }}
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h4 style="font-size: 13px; font-weight: 700; color: var(--primary-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px;">
                                    {{ $cust->name }}
                                </h4>
                                <span class="id-value" style="font-size: 10px;">{{ $cust->customer_id }}</span>
                            </div>
                            <div>
                                <span class="badge badge-{{ $cust->status ? 'active' : 'inactive' }}" style="font-size: 9px; padding: 2px 6px;">
                                    {{ $cust->status ? 'aktif' : 'nonaktif' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endcomponent
