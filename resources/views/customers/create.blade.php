@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('customers.index') }}">Customers</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Tambah Customer</span>
</div>

<div class="form-card" style="margin: 0 auto;">
    <div class="form-card-header">
        <div class="form-card-header-icon"><iconify-icon icon="mdi:account-group"></iconify-icon></div>
        <div>
            <h2>Registrasi Pelanggan Baru</h2>
            <p>Daftarkan profil pelanggan baru ke sistem ERP</p>
        </div>
    </div>
    
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="form-card-body">
            
            {{-- Name --}}
            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap <span style="color: var(--red);">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control @error('name') form-error @enderror" 
                       placeholder="Masukkan nama pelanggan..." value="{{ old('name') }}" required>
                @error('name')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" id="email" name="email" 
                           class="form-control @error('email') form-error @enderror" 
                           placeholder="contoh@email.com" value="{{ old('email') }}">
                    @error('email')
                        <div class="form-error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="form-group">
                    <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" 
                           class="form-control @error('phone') form-error @enderror" 
                           placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                    @error('phone')
                        <div class="form-error-msg">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Address --}}
            <div class="form-group">
                <label for="address" class="form-label">Alamat Lengkap</label>
                <textarea id="address" name="address" 
                          class="form-control @error('address') form-error @enderror" 
                          placeholder="Masukkan alamat lengkap rumah / kantor...">{{ old('address') }}</textarea>
                @error('address')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status" class="form-label">Status Awal</label>
                <select id="status" name="status" class="form-control @error('status') form-error @enderror">
                    <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

        </div>
        
        <div class="form-actions">
            <a href="{{ route('customers.index') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Customer</button>
        </div>
    </form>
</div>

@endcomponent
