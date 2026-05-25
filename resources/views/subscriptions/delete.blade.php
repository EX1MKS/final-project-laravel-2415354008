@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('subscriptions.index') }}">Subscriptions</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Hapus Subscription</span>
</div>

<div class="delete-card" style="margin: 0 auto;">
    <div class="delete-icon-wrap" style="background: var(--light-red); color: var(--red);">
        🗑️
    </div>

    <h2>Hapus Subscription?</h2>
    <p>Tindakan ini akan menghapus data langganan berikut secara permanen dari sistem ERP.</p>
    
    <div class="delete-info-box">
        <div class="delete-info-row">
            <span>Pelanggan:</span>
            <strong>{{ $subscription->customer->name }} ({{ $subscription->customer->customer_id }})</strong>
        </div>
        <div class="delete-info-row">
            <span>Layanan:</span>
            <strong>{{ $subscription->service->name }}</strong>
        </div>
        <div class="delete-info-row">
            <span>Harga Bulanan:</span>
            <strong class="price-value">Rp {{ number_format($subscription->service->price, 0, ',', '.') }}</strong>
        </div>
        <div class="delete-info-row">
            <span>Tanggal Mulai:</span>
            <strong>{{ $subscription->start_date ? $subscription->start_date->format('d M Y') : '-' }}</strong>
        </div>
        <div class="delete-info-row">
            <span>Status:</span>
            <span class="badge badge-{{ $subscription->status }}">{{ $subscription->status }}</span>
        </div>
    </div>

    <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="delete-actions">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-danger">Hapus Permanen</button>
        </div>
    </form>
</div>

@endcomponent
