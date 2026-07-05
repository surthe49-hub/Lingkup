@csrf

<div class="lingkup-card lingkup-form-card">
    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="flag_emoji" class="lingkup-form-label">Emoji Bendera <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="flag_emoji"
                   name="flag_emoji"
                   value="{{ old('flag_emoji', $destination->flag_emoji ?? '') }}"
                   class="lingkup-form-input @error('flag_emoji') lingkup-form-input-error @enderror"
                   placeholder="Contoh: 🇯🇵">
            <span class="lingkup-form-hint">Copy-paste emoji bendera dari <a href="https://emojipedia.org/flags" target="_blank" rel="noopener">emojipedia.org/flags</a>.</span>
            @error('flag_emoji')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="lingkup-form-group">
            <label for="name" class="lingkup-form-label">Nama Negara <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $destination->name ?? '') }}"
                   class="lingkup-form-input @error('name') lingkup-form-input-error @enderror"
                   placeholder="Contoh: Jepang">
            @error('name')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-group">
        <label for="scholarship_name" class="lingkup-form-label">Nama Beasiswa <span class="lingkup-form-required">*</span></label>
        <input type="text"
               id="scholarship_name"
               name="scholarship_name"
               value="{{ old('scholarship_name', $destination->scholarship_name ?? '') }}"
               class="lingkup-form-input @error('scholarship_name') lingkup-form-input-error @enderror"
               placeholder="Contoh: MEXT Scholarship">
        @error('scholarship_name')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-group">
        <label for="image" class="lingkup-form-label">
            Gambar Negara {{ isset($destination) ? '' : '' }}
            @if (! isset($destination))
                <span class="lingkup-form-required">*</span>
            @endif
        </label>

        @if (isset($destination))
            <div class="lingkup-destination-current-image">
                <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" class="lingkup-destination-thumb-lg">
                <span class="lingkup-form-hint">Gambar saat ini. Upload file baru di bawah untuk mengganti.</span>
            </div>
        @endif

        <input type="file"
               id="image"
               name="image"
               accept="image/jpeg,image/png,image/webp"
               class="lingkup-form-input @error('image') lingkup-form-input-error @enderror">
        <span class="lingkup-form-hint">Format JPG/PNG/WEBP, maksimal 2MB.</span>
        @error('image')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="display_order" class="lingkup-form-label">Urutan Tampil</label>
            <input type="number"
                   id="display_order"
                   name="display_order"
                   min="0"
                   value="{{ old('display_order', $destination->display_order ?? $nextOrder ?? 0) }}"
                   class="lingkup-form-input @error('display_order') lingkup-form-input-error @enderror">
            <span class="lingkup-form-hint">Angka lebih kecil tampil lebih dulu.</span>
            @error('display_order')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-group lingkup-form-checkbox-group">
        <label class="lingkup-form-checkbox-label">
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   {{ old('is_active', $destination->is_active ?? true) ? 'checked' : '' }}>
            Tampilkan di halaman publik /home
        </label>
    </div>

    <div class="lingkup-form-actions">
        <a href="{{ route('admin.study-destinations.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($destination) ? 'Perbarui Negara' : 'Simpan Negara' }}
        </button>
    </div>
</div>