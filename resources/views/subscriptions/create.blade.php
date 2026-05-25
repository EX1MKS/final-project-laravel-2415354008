@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('subscriptions.index') }}">Subscriptions</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Tambah Subscription</span>
</div>

<div class="form-card" style="margin: 0 auto;">
    <div class="form-card-header">
        <div class="form-card-header-icon"><iconify-icon icon="mdi:clipboard-text"></iconify-icon></div>
        <div>
            <h2>Registrasi Langganan Baru</h2>
            <p>Daftarkan paket layanan digital ke akun pelanggan</p>
        </div>
    </div>
    
    <form action="{{ route('subscriptions.store') }}" method="POST">
        @csrf
        <div class="form-card-body">
            
            {{-- Customer --}}
            <div class="form-group">
                <label for="customer_id" class="form-label">Pelanggan (Aktif) <span style="color: var(--red);">*</span></label>
                <select id="customer_id" name="customer_id" class="form-control @error('customer_id') form-error @enderror" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->customer_id }})
                        </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Service --}}
            <div class="form-group">
                <label for="service_id" class="form-label">Layanan Digital (Aktif) <span style="color: var(--red);">*</span></label>
                <select id="service_id" name="service_id" class="form-control @error('service_id') form-error @enderror" required>
                    <option value="">-- Pilih Layanan --</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }} — Rp {{ number_format($s->price, 0, ',', '.') }}/bln
                        </option>
                    @endforeach
                </select>
                @error('service_id')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                {{-- Start Date --}}
                <div class="form-group">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" 
                           class="form-control @error('start_date') form-error @enderror" 
                           value="{{ old('start_date', date('Y-m-d')) }}">
                    @error('start_date')
                        <div class="form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- End Date --}}
                <div class="form-group">
                    <label for="end_date" class="form-label">Tanggal Berakhir</label>
                    <input type="date" id="end_date" name="end_date" 
                           class="form-control @error('end_date') form-error @enderror" 
                           value="{{ old('end_date') }}">
                    <div class="form-hint">Kosongkan jika berlangganan tanpa batas waktu (selamanya).</div>
                    @error('end_date')
                        <div class="form-error-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status" class="form-label">Status Awal</label>
                <select id="status" name="status" class="form-control @error('status') form-error @enderror">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ old('status', 'trial') === $st ? 'selected' : '' }}>
                            {{ strtoupper($st) }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

        </div>
        
        <div class="form-actions">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Subscription</button>
        </div>
    </form>
</div>

@endcomponent
