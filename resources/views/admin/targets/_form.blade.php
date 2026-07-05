@csrf

<div class="lingkup-card lingkup-form-card">
    <div class="lingkup-form-group">
        <label for="name" class="lingkup-form-label">Nama Target <span class="lingkup-form-required">*</span></label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name', $target->name ?? '') }}"
               class="lingkup-form-input @error('name') lingkup-form-input-error @enderror"
               placeholder="Contoh: Chevening Scholarship">
        @error('name')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="country" class="lingkup-form-label">Negara <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="country"
                   name="country"
                   value="{{ old('country', $target->country ?? '') }}"
                   class="lingkup-form-input @error('country') lingkup-form-input-error @enderror"
                   placeholder="Contoh: Inggris">
            @error('country')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="lingkup-form-group">
            <label for="education_level" class="lingkup-form-label">Jenjang <span class="lingkup-form-required">*</span></label>
            <select id="education_level" name="education_level" class="lingkup-form-select @error('education_level') lingkup-form-input-error @enderror">
                @php $currentLevel = old('education_level', $target->education_level ?? ''); @endphp
                <option value="" disabled {{ $currentLevel === '' ? 'selected' : '' }}>Pilih jenjang</option>
                @foreach (['S1', 'S2', 'S3', 'Exchange', 'Internship'] as $level)
                    <option value="{{ $level }}" {{ $currentLevel === $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
            @error('education_level')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-group">
        <label for="program_type" class="lingkup-form-label">Tipe Program <span class="lingkup-form-required">*</span></label>
        @php $currentType = old('program_type', $target->program_type ?? 'scholarship'); @endphp
        <select id="program_type" name="program_type" class="lingkup-form-select @error('program_type') lingkup-form-input-error @enderror">
            @foreach (['scholarship' => 'Scholarship', 'exchange' => 'Exchange', 'internship' => 'Internship', 'dual_degree' => 'Dual Degree'] as $value => $label)
                <option value="{{ $value }}" {{ $currentType === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('program_type')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-group">
        <label for="requirements_summary" class="lingkup-form-label">Ringkasan Persyaratan <span class="lingkup-form-required">*</span></label>
        <textarea id="requirements_summary"
                  name="requirements_summary"
                  rows="4"
                  class="lingkup-form-textarea @error('requirements_summary') lingkup-form-input-error @enderror"
                  placeholder="Ringkasan persyaratan dalam bahasa natural, akan ditampilkan ke user.">{{ old('requirements_summary', $target->requirements_summary ?? '') }}</textarea>
        @error('requirements_summary')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-group">
        <label for="structured_requirements" class="lingkup-form-label">Structured Requirements (JSON, opsional)</label>
        <textarea id="structured_requirements"
                  name="structured_requirements"
                  rows="6"
                  class="lingkup-form-textarea lingkup-form-textarea-code @error('structured_requirements') lingkup-form-input-error @enderror"
                  placeholder='Contoh: {"ipk_minimum": 3.0, "bahasa": "IELTS 6.5"}'>{{ old('structured_requirements', isset($target) && $target->structured_requirements ? json_encode($target->structured_requirements, JSON_PRETTY_PRINT) : '') }}</textarea>
        <span class="lingkup-form-hint">
            Kosongkan jika tidak ada. Harus format JSON valid kalau diisi — dipakai sebagai konteks tambahan untuk AI pathway generation.
        </span>
        @error('structured_requirements')
            <span class="lingkup-form-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="lingkup-form-row">
        <div class="lingkup-form-group">
            <label for="typical_deadline" class="lingkup-form-label">Deadline Tipikal</label>
            <input type="text"
                   id="typical_deadline"
                   name="typical_deadline"
                   value="{{ old('typical_deadline', $target->typical_deadline ?? '') }}"
                   class="lingkup-form-input @error('typical_deadline') lingkup-form-input-error @enderror"
                   placeholder="Contoh: November setiap tahun">
            @error('typical_deadline')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="lingkup-form-group">
            <label for="official_url" class="lingkup-form-label">URL Resmi <span class="lingkup-form-required">*</span></label>
            <input type="text"
                   id="official_url"
                   name="official_url"
                   value="{{ old('official_url', $target->official_url ?? '') }}"
                   class="lingkup-form-input @error('official_url') lingkup-form-input-error @enderror"
                   placeholder="https://...">
            @error('official_url')
                <span class="lingkup-form-error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="lingkup-form-group lingkup-form-checkbox-group">
        <label class="lingkup-form-checkbox-label">
            <input type="checkbox"
                   name="is_active"
                   value="1"
                   {{ old('is_active', $target->is_active ?? true) ? 'checked' : '' }}>
            Aktifkan target ini (muncul di pilihan user)
        </label>
    </div>

    <div class="lingkup-form-actions">
        <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">
            {{ isset($target) ? 'Perbarui Target' : 'Simpan Target' }}
        </button>
    </div>
</div>