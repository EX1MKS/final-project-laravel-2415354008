@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('services.index') }}">Services</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Hapus Service</span>
</div>

@php
    $subCount = $service->subscriptions()->count();
@endphp

<div class="delete-card" style="margin: 0 auto;">
    <div class="delete-icon-wrap" style="background: {{ $subCount > 0 ? 'var(--light-primary)' : 'var(--light-red)' }}; color: {{ $subCount > 0 ? 'var(--primary)' : 'var(--red)' }}; display: flex; align-items: center; justify-content: center;">
        @if($subCount > 0)
            <iconify-icon icon="mdi:shield-check" style="font-size: 32px;"></iconify-icon>
        @else
            <iconify-icon icon="mdi:trash-can" style="font-size: 32px;"></iconify-icon>
        @endif
    </div>

    @if($subCount > 0)
        <h2>Tidak Dapat Menghapus Service</h2>
        <p>Layanan <strong>{{ $service->name }}</strong> saat ini masih digunakan oleh <strong>{{ $subCount }}</strong> langganan pelanggan.</p>
        <p style="font-size: 13px; color: var(--gray);">Anda tidak dapat menghapus layanan yang masih aktif digunakan pelanggan.</p>
        
        <div class="delete-actions">
            <a href="{{ route('services.index') }}" class="btn btn-primary">Kembali ke Daftar</a>
            <a href="{{ route('subscriptions.index', ['search' => $service->name]) }}" class="btn btn-secondary">Lihat Subscription</a>
        </div>
    @else
        <h2>Apakah Anda yakin?</h2>
        <p>Tindakan ini akan menghapus data layanan berikut secara permanen dari database ERP.</p>
        
        <div class="delete-info-box">
            <div class="delete-info-row">
                <span>Nama Layanan:</span>
                <strong>{{ $service->name }}</strong>
            </div>
            <div class="delete-info-row">
                <span>Harga Bulanan:</span>
                <strong class="price-value">Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
            </div>
            <div class="delete-info-row">
                <span>Status:</span>
                <strong style="color: {{ $service->status ? 'var(--green)' : 'var(--gray)' }}">
                    {{ $service->status ? 'Aktif' : 'Nonaktif' }}
                </strong>
            </div>
        </div>

        <form action="{{ route('services.destroy', $service) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="delete-actions">
                <a href="{{ route('services.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-danger">Hapus Permanen</button>
            </div>
        </form>
    @endif
</div>

@endcomponent
