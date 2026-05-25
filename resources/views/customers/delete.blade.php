@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('customers.index') }}">Customers</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Hapus Customer</span>
</div>

<div class="delete-card" style="margin: 0 auto;">
    <div class="delete-icon-wrap" style="background: {{ $subscriptionCount > 0 ? 'var(--light-primary)' : 'var(--light-red)' }}; color: {{ $subscriptionCount > 0 ? 'var(--primary)' : 'var(--red)' }};">
        {{ $subscriptionCount > 0 ? '🛡️' : '🗑️' }}
    </div>

    @if($subscriptionCount > 0)
        <h2>Tidak Dapat Menghapus Pelanggan</h2>
        <p>Pelanggan <strong>{{ $customer->name }}</strong> saat ini masih memiliki <strong>{{ $subscriptionCount }}</strong> langganan aktif.</p>
        <p style="font-size: 13px; color: var(--gray);">Anda harus membatalkan atau menghapus semua subscription pelanggan ini terlebih dahulu sebelum menghapus profilnya.</p>
        
        <div class="delete-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-primary">Kembali ke Daftar</a>
            <a href="{{ route('subscriptions.index', ['search' => $customer->name]) }}" class="btn btn-secondary">Lihat Subscription</a>
        </div>
    @else
        <h2>Apakah Anda yakin?</h2>
        <p>Tindakan ini akan menghapus data pelanggan berikut secara permanen dari sistem ERP.</p>
        
        <div class="delete-info-box">
            <div class="delete-info-row">
                <span>Nama:</span>
                <strong>{{ $customer->name }}</strong>
            </div>
            <div class="delete-info-row">
                <span>Customer ID:</span>
                <strong class="id-value">{{ $customer->customer_id }}</strong>
            </div>
            <div class="delete-info-row">
                <span>Email:</span>
                <strong>{{ $customer->email ?? '-' }}</strong>
            </div>
            <div class="delete-info-row">
                <span>Status:</span>
                <strong style="color: {{ $customer->status ? 'var(--green)' : 'var(--gray)' }}">
                    {{ $customer->status ? 'Aktif' : 'Nonaktif' }}
                </strong>
            </div>
        </div>

        <form action="{{ route('customers.destroy', $customer) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="delete-actions">
                <a href="{{ route('customers.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
            </div>
        </form>
    @endif
</div>

@endcomponent
