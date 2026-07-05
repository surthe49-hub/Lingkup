@csrf

<div class="lingkup-card lingkup-form-card">
    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="name" class="lingkup-form-label">Nama <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name', $testimonial->name ?? '') }}"
                   class="lingkup-form-input @error('name') lingkup-form-input-error @enderror"
                   placeholder="Contoh: Andini">
            @error('name')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="lingkup-form-group">
            <label for="role" class="lingkup-form-label">Role / Status <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="role"
                   name="role"
                   value="{{ old('role', $testimonial->role ?? '') }}"
                   class="lingkup-form-input @error('role') lingkup-form-input-error @enderror"
                   placeholder="Contoh: Penerima Beasiswa AAS 2025">
            @error('role')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="avatar_color" class="lingkup-form-label">Warna Avatar <span class="lingkup-form-required">*</span></label>
            @php $currentColor = old('avatar_color', $testimonial->avatar_color ?? 'primary'); @endphp
            <select id="avatar_color" name="avatar_color" class="lingkup-form-select @error('avatar_color') lingkup-form-input-error @enderror">
                @foreach (['primary' => 'Primary (Ungu)', 'peach' => 'Peach (Oranye)', 'teal' => 'Teal (Hijau Tosca)', 'green' => 'Green (Hijau)', 'pink' => 'Pink'] as $value => $label)
                    <option value="{{ $value }}" {{ $currentColor === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('avatar_color')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="lingkup-form-group">
            <label for="rating" class="lingkup-form-label">Rating <span class="lingkup-form-required">*</span></label>
            @php $currentRating = old('rating', $testimonial->rating ?? 5); @endphp
            <select id="rating" name="rating" class="lingkup-form-select @error('rating') lingkup-form-input-error @enderror">
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ (int) $currentRating === $i ? 'selected' : '' }}>{{ $i }} Bintang</option>
                @endfor
            </select>
            @error('rating')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-group">
        <label for="message" class="lingkup-form-label">Isi Testimonial <span class="lingkup-form-required">*</span></label>
        <textarea id="message"
                  name="message"
                  rows="4"
                  class="lingkup-form-textarea @error('message') lingkup-form-input-error @enderror"
                  placeholder="Kutipan testimonial...">{{ old('message', $testimonial->message ?? '') }}</textarea>
        @error('message')
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
                   value="{{ old('display_order', $testimonial->display_order ?? $nextOrder ?? 0) }}"
                   class="lingkup-form-input @error('display_order') lingkup-form-input-error @enderror">
            <span class="lingkup-form-hint">Angka lebih kecil tampil lebih dulu di halaman publik.</span>
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
                   {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
            Tampilkan di halaman publik /reviews
        </label>
    </div>

    <div class="lingkup-form-actions">
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($testimonial) ? 'Perbarui Testimonial' : 'Simpan Testimonial' }}
        </button>
    </div>
</div>