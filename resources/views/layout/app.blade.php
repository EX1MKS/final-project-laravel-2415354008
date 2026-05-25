<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ERP System' }} — Digital Services</title>

    {{-- Google Fonts (preconnect dipindah ke sini cukup sekali) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    {{-- ERP Custom CSS (plain CSS, no build step needed) --}}
    <link rel="stylesheet" href="{{ asset('css/erp.css') }}">

    <!-- iconfy -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>

    @stack('styles')
</head>
<body>

<div class="erp-layout">

    {{-- Sidebar --}}
    @include('components.sidebar')

    {{-- Main Content --}}
    <div class="main-content">

        {{-- Topbar --}}
        <header class="topbar">
            <div class="topbar-left">
                <h1>{{ $title ?? 'Dashboard' }}</h1>
                <p>{{ $subtitle ?? 'ERP Digital Services' }}</p>
            </div>
            <div class="topbar-right">
                <span class="topbar-date">
                    <iconify-icon icon="mdi:calendar" style="vertical-align:-3px;"></iconify-icon>
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-wrapper">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <iconify-icon icon="mdi:check-circle" style="font-size:18px;"></iconify-icon>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <iconify-icon icon="mdi:close-circle" style="font-size:18px;"></iconify-icon>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <iconify-icon icon="mdi:alert" style="font-size:18px;"></iconify-icon>
                    <div>
                        <strong>Terdapat kesalahan:</strong>
                        <ul style="margin: 6px 0 0 16px; font-size: 12.5px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Slot --}}
            {{ $slot }}

        </main>
    </div>
</div>

@stack('scripts')

{{-- Auto-dismiss flash alerts --}}
<script>
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s ease, transform .4s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-6px)';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
</script>
</body>
</html>