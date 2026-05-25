@component('layout.app', ['title' => $title, 'subtitle' => $subtitle])

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <span class="breadcrumb-sep">/</span>
    <a href="{{ route('services.index') }}">Services</a>
    <span class="breadcrumb-sep">/</span>
    <span class="breadcrumb-current">Edit Service</span>
</div>

<div class="form-card" style="margin: 0 auto;">
    <div class="form-card-header">
        <div class="form-card-header-icon">⚙️</div>
        <div>
            <h2>Edit Layanan Digital</h2>
            <p>Perbarui detail paket dan spesifikasi layanan</p>
        </div>
    </div>
    
    <form action="{{ route('services.update', $service) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-card-body">
            
            {{-- Name --}}
            <div class="form-group">
                <label for="name" class="form-label">Nama Layanan <span style="color: var(--red);">*</span></label>
                <input type="text" id="name" name="name" 
                       class="form-control @error('name') form-error @enderror" 
                       placeholder="Contoh: VPS Business, Shared Hosting Pro..." value="{{ old('name', $service->name) }}" required>
                @error('name')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Price --}}
            <div class="form-group">
                <label for="price" class="form-label">Harga Bulanan (Rp) <span style="color: var(--red);">*</span></label>
                <input type="number" id="price" name="price" min="0"
                       class="form-control @error('price') form-error @enderror" 
                       placeholder="Masukkan nominal harga bulanan..." value="{{ old('price', $service->price) }}" required>
                @error('price')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description" class="form-label">Deskripsi Layanan</label>
                <textarea id="description" name="description" 
                          class="form-control @error('description') form-error @enderror" 
                          placeholder="Masukkan detail spesifikasi atau info tambahan layanan...">{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-control @error('status') form-error @enderror">
                    <option value="1" {{ old('status', $service->status ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('status', $service->status ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <div class="form-error-msg">{{ $message }}</div>
                @enderror
            </div>

        </div>
        
        <div class="form-actions">
            <a href="{{ route('services.index') }}" class="btn btn-ghost">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

@endcomponent
