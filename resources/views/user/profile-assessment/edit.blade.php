@extends('layouts.dashboard')

@section('breadcrumb', 'Profil Akademik · ' . ($profile ? 'Edit' : 'Mulai'))

@section('content')
    @php
        $isEditing = $profile !== null;
        $formAction = $isEditing ? route('profile-assessment.update') : route('profile-assessment.store');
        $formMethod = $isEditing ? 'PUT' : 'POST';

        // Existing data untuk pre-fill
        $existingLanguages = old('other_languages', $profile?->other_languages ?? []);
        $existingSkills = old('current_skills', $profile?->current_skills ?? []);
        $existingInterests = old('interests', $profile?->interests ?? []);
    @endphp

    <x-page-header
        :title="$isEditing ? 'Edit Profil Akademik' : 'Lengkapi Profil Akademik'"
        subtitle="Isi data berikut. Field bertanda * wajib diisi. Estimasi ± 5 menit." />

    {{-- Global Error Banner --}}
    @if ($errors->any())
        <div class="lingkup-error-banner">
            <p class="lingkup-error-banner-title">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Mohon perbaiki {{ $errors->count() }} kesalahan berikut:
            </p>
            <ul class="lingkup-error-banner-list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $formAction }}" method="POST" id="profile-assessment-form">
        @csrf
        @method($formMethod)

        {{-- ============================================ --}}
        {{-- SECTION 1: Data Akademik                      --}}
        {{-- ============================================ --}}
        <div class="lingkup-card mb-4">
            <div class="lingkup-section-header">
                <span class="lingkup-section-number">1</span>
                <div class="lingkup-section-header-text">
                    <h3>Data Akademik</h3>
                    <p>Informasi dasar tentang studimu saat ini</p>
                </div>
            </div>

            <div class="row g-3">
                {{-- Major --}}
                <div class="col-md-12">
                    <div class="lingkup-form-group">
                        <label for="major" class="lingkup-form-label">
                            Jurusan / Program Studi <span class="lingkup-required">*</span>
                        </label>
                        <input
                            type="text"
                            id="major"
                            name="major"
                            value="{{ old('major', $profile?->major) }}"
                            class="lingkup-form-control @error('major') is-invalid @enderror"
                            placeholder="Contoh: Teknik Informatika"
                            required
                            maxlength="100">
                        @error('major')
                            <div class="lingkup-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Education Level --}}
                <div class="col-md-6">
                    <div class="lingkup-form-group">
                        <label for="education_level" class="lingkup-form-label">
                            Jenjang Pendidikan <span class="lingkup-required">*</span>
                        </label>
                        <select
                            id="education_level"
                            name="education_level"
                            class="lingkup-form-control @error('education_level') is-invalid @enderror"
                            required>
                            <option value="">— Pilih jenjang —</option>
                            @foreach (['D3', 'S1', 'S2', 'S3'] as $level)
                                <option value="{{ $level }}"
                                    {{ old('education_level', $profile?->education_level) === $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('education_level')
                            <div class="lingkup-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Semester --}}
                <div class="col-md-3">
                    <div class="lingkup-form-group">
                        <label for="semester" class="lingkup-form-label">
                            Semester <span class="lingkup-required">*</span>
                        </label>
                        <input
                            type="number"
                            id="semester"
                            name="semester"
                            value="{{ old('semester', $profile?->semester) }}"
                            class="lingkup-form-control @error('semester') is-invalid @enderror"
                            min="1"
                            max="14"
                            placeholder="5"
                            required>
                        @error('semester')
                            <div class="lingkup-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- GPA --}}
                <div class="col-md-3">
                    <div class="lingkup-form-group">
                        <label for="gpa" class="lingkup-form-label">
                            IPK <span class="lingkup-required">*</span>
                        </label>
                        <input
                            type="number"
                            id="gpa"
                            name="gpa"
                            value="{{ old('gpa', $profile?->gpa) }}"
                            class="lingkup-form-control @error('gpa') is-invalid @enderror"
                            step="0.01"
                            min="0"
                            max="4"
                            placeholder="3.45"
                            required>
                        @error('gpa')
                            <div class="lingkup-form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 2: Kemampuan Bahasa                   --}}
        {{-- ============================================ --}}
        <div class="lingkup-card mb-4">
            <div class="lingkup-section-header">
                <span class="lingkup-section-number">2</span>
                <div class="lingkup-section-header-text">
                    <h3>Kemampuan Bahasa</h3>
                    <p>Tingkat penguasaan bahasa Inggris dan bahasa asing lain</p>
                </div>
            </div>

            {{-- English Level --}}
            <div class="lingkup-form-group">
                <label class="lingkup-form-label">
                    Tingkat Bahasa Inggris <span class="lingkup-required">*</span>
                </label>
                <div class="lingkup-radio-cards">
                    @php
                        $englishLevels = [
                            'beginner' => ['label' => 'Beginner', 'desc' => 'Dasar'],
                            'intermediate' => ['label' => 'Intermediate', 'desc' => 'Menengah'],
                            'advanced' => ['label' => 'Advanced', 'desc' => 'Mahir'],
                            'native' => ['label' => 'Native', 'desc' => 'Setara penutur asli'],
                        ];
                        $selectedLevel = old('english_level', $profile?->english_level);
                    @endphp

                    @foreach ($englishLevels as $value => $info)
                        <label class="lingkup-radio-card">
                            <input type="radio" name="english_level" value="{{ $value }}"
                                {{ $selectedLevel === $value ? 'checked' : '' }}
                                required>
                            <div>
                                <div style="font-weight: 600; margin-bottom: 0.125rem;">{{ $info['label'] }}</div>
                                <div style="font-size: 0.8125rem; color: var(--lingkup-text-muted);">{{ $info['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('english_level')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Sub-section: Test Score --}}
            <div style="margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--lingkup-border);">
                <h4 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: var(--space-xs);">
                    Sertifikasi Tes Bahasa Inggris
                </h4>
                <p style="font-size: 0.875rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-md);">
                    Opsional — isi keduanya jika kamu sudah pernah mengambil tes.
                </p>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="lingkup-form-group">
                            <label for="english_test_type" class="lingkup-form-label">Jenis Tes</label>
                            <select
                                id="english_test_type"
                                name="english_test_type"
                                class="lingkup-form-control @error('english_test_type') is-invalid @enderror">
                                <option value="">— Tidak ada —</option>
                                @foreach (['TOEFL_ITP' => 'TOEFL ITP', 'TOEFL_IBT' => 'TOEFL iBT', 'IELTS' => 'IELTS', 'DUOLINGO' => 'Duolingo English Test'] as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('english_test_type', $profile?->english_test_type) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('english_test_type')
                                <div class="lingkup-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="lingkup-form-group">
                            <label for="english_test_score" class="lingkup-form-label">Skor</label>
                            <input
                                type="number"
                                id="english_test_score"
                                name="english_test_score"
                                value="{{ old('english_test_score', $profile?->english_test_score) }}"
                                class="lingkup-form-control @error('english_test_score') is-invalid @enderror"
                                min="0"
                                max="1000"
                                placeholder="Contoh: 550">
                            @error('english_test_score')
                                <div class="lingkup-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sub-section: Other Languages --}}
            <div style="margin-top: var(--space-lg); padding-top: var(--space-lg); border-top: 1px solid var(--lingkup-border);">
                <h4 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: var(--space-xs);">
                    Bahasa Lain yang Dikuasai
                </h4>
                <p style="font-size: 0.875rem; color: var(--lingkup-text-muted); margin-bottom: var(--space-md);">
                    Opsional — tambahkan bahasa asing selain Inggris.
                </p>

                <div id="other-languages-container">
                    @foreach ($existingLanguages as $index => $lang)
                        <div class="lingkup-array-item">
                            <input
                                type="text"
                                name="other_languages[{{ $index }}][lang]"
                                value="{{ $lang['lang'] ?? '' }}"
                                class="lingkup-form-control"
                                placeholder="Bahasa (mis. Korean)"
                                maxlength="50">
                            <select name="other_languages[{{ $index }}][level]" class="lingkup-form-control">
                                <option value="">— Pilih tingkat —</option>
                                @foreach (['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced', 'native' => 'Native'] as $value => $label)
                                    <option value="{{ $value }}" {{ ($lang['level'] ?? '') === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="lingkup-array-remove" onclick="removeArrayItem(this)" aria-label="Hapus">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="lingkup-array-add" onclick="addLanguage()">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Bahasa
                </button>
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 3: Skill & Pengalaman                 --}}
        {{-- ============================================ --}}
        <div class="lingkup-card mb-4">
            <div class="lingkup-section-header">
                <span class="lingkup-section-number">3</span>
                <div class="lingkup-section-header-text">
                    <h3>Skill & Pengalaman</h3>
                    <p>Keahlian, pengalaman organisasi, dan minat bidangmu (opsional)</p>
                </div>
            </div>

            {{-- Current Skills --}}
            <div class="lingkup-form-group">
                <label class="lingkup-form-label">Current Skills</label>
                <div class="lingkup-tag-input" id="skills-tag-input" onclick="document.getElementById('skills-input').focus()">
                    <div id="skills-tags-container" style="display: flex; flex-wrap: wrap; gap: 0.375rem; align-items: center;"></div>
                    <input
                        type="text"
                        id="skills-input"
                        placeholder="Ketik skill dan tekan Enter (mis. Python)"
                        onkeydown="handleTagInput(event, 'current_skills', 'skills-tags-container', 'skills-input')"
                        maxlength="50">
                </div>
                <div class="lingkup-form-help">
                    Maks 20 skills · Tekan Enter setelah mengetik untuk menambahkan
                </div>
                @error('current_skills')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Organization Experience --}}
            <div class="lingkup-form-group">
                <label for="organization_experience" class="lingkup-form-label">
                    Pengalaman Organisasi
                </label>
                <textarea
                    id="organization_experience"
                    name="organization_experience"
                    rows="4"
                    class="lingkup-form-control @error('organization_experience') is-invalid @enderror"
                    placeholder="Ceritakan pengalaman organisasi, kepanitiaan, atau kepemimpinan kamu..."
                    maxlength="1000"
                    oninput="updateCharCounter(this, 'org-counter')">{{ old('organization_experience', $profile?->organization_experience) }}</textarea>
                <div class="lingkup-char-counter" id="org-counter">0 / 1000 karakter</div>
                @error('organization_experience')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Interests --}}
            <div class="lingkup-form-group">
                <label class="lingkup-form-label">Minat Bidang</label>
                <div class="lingkup-tag-input" id="interests-tag-input" onclick="document.getElementById('interests-input').focus()">
                    <div id="interests-tags-container" style="display: flex; flex-wrap: wrap; gap: 0.375rem; align-items: center;"></div>
                    <input
                        type="text"
                        id="interests-input"
                        placeholder="Ketik minat dan tekan Enter (mis. AI, Sustainability)"
                        onkeydown="handleTagInput(event, 'interests', 'interests-tags-container', 'interests-input')"
                        maxlength="50">
                </div>
                <div class="lingkup-form-help">
                    Maks 15 minat · Tekan Enter setelah mengetik untuk menambahkan
                </div>
                @error('interests')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- ============================================ --}}
        {{-- SECTION 4: Target Karier                      --}}
        {{-- ============================================ --}}
        <div class="lingkup-card mb-4">
            <div class="lingkup-section-header">
                <span class="lingkup-section-number">4</span>
                <div class="lingkup-section-header-text">
                    <h3>Target Karier</h3>
                    <p>Cita-cita dan negara tujuanmu (opsional, tapi sangat membantu AI)</p>
                </div>
            </div>

            {{-- Target Country --}}
            <div class="lingkup-form-group">
                <label for="target_country" class="lingkup-form-label">Negara Tujuan</label>
                <input
                    type="text"
                    id="target_country"
                    name="target_country"
                    value="{{ old('target_country', $profile?->target_country) }}"
                    class="lingkup-form-control @error('target_country') is-invalid @enderror"
                    placeholder="Contoh: Korea Selatan, Jerman, Australia"
                    maxlength="100"
                    list="country-suggestions">
                <datalist id="country-suggestions">
                    <option value="Korea Selatan">
                    <option value="Jepang">
                    <option value="Amerika Serikat">
                    <option value="Inggris">
                    <option value="Jerman">
                    <option value="Belanda">
                    <option value="Australia">
                    <option value="Singapura">
                    <option value="Kanada">
                    <option value="Prancis">
                </datalist>
                @error('target_country')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>

            {{-- Career Goal --}}
            <div class="lingkup-form-group">
                <label for="career_goal" class="lingkup-form-label">Tujuan Karier</label>
                <textarea
                    id="career_goal"
                    name="career_goal"
                    rows="4"
                    class="lingkup-form-control @error('career_goal') is-invalid @enderror"
                    placeholder="Ceritakan tujuan karier dan studimu dalam 3-5 tahun ke depan..."
                    maxlength="1000"
                    oninput="updateCharCounter(this, 'career-counter')">{{ old('career_goal', $profile?->career_goal) }}</textarea>
                <div class="lingkup-char-counter" id="career-counter">0 / 1000 karakter</div>
                @error('career_goal')
                    <div class="lingkup-form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Form Footer --}}
        <div class="lingkup-form-footer">
            <a href="{{ route('profile-assessment.index') }}" class="btn btn-light">
                Batal
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check2 me-1"></i> {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Profil' }}
            </button>
        </div>

        <p style="text-align: center; font-size: 0.8125rem; color: var(--lingkup-text-muted); margin-top: var(--space-md);">
            <i class="bi bi-shield-lock me-1"></i>
            Data kamu hanya digunakan untuk personalisasi pathway. Tidak akan dibagikan.
        </p>
    </form>

    {{-- ============================================ --}}
    {{-- JavaScript: Tag input + Dynamic array         --}}
    {{-- ============================================ --}}
    <script>
        // ============================================
        // State untuk tag input
        // ============================================
        const skillsState = @json($existingSkills);
        const interestsState = @json($existingInterests);

        const tagLimits = {
            current_skills: 20,
            interests: 15,
        };

        // ============================================
        // Render tags
        // ============================================
        function renderTags(fieldName, containerId, state) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            state.forEach((tag, index) => {
                const tagEl = document.createElement('span');
                tagEl.className = 'lingkup-tag';
                tagEl.innerHTML = `
                    <span>${escapeHtml(tag)}</span>
                    <button type="button" class="lingkup-tag-remove" onclick="removeTag('${fieldName}', '${containerId}', ${index})" aria-label="Hapus">
                        <i class="bi bi-x"></i>
                    </button>
                    <input type="hidden" name="${fieldName}[]" value="${escapeHtml(tag)}">
                `;
                container.appendChild(tagEl);
            });
        }

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        function getState(fieldName) {
            return fieldName === 'current_skills' ? skillsState : interestsState;
        }

        // ============================================
        // Handle Enter di tag input
        // ============================================
        function handleTagInput(event, fieldName, containerId, inputId) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                const input = document.getElementById(inputId);
                const value = input.value.trim();
                const state = getState(fieldName);

                if (value === '') return;

                if (state.length >= tagLimits[fieldName]) {
                    alert(`Maksimal ${tagLimits[fieldName]} item.`);
                    return;
                }

                if (state.includes(value)) {
                    input.value = '';
                    return;
                }

                state.push(value);
                input.value = '';
                renderTags(fieldName, containerId, state);
            } else if (event.key === 'Backspace' && event.target.value === '') {
                const state = getState(fieldName);
                if (state.length > 0) {
                    state.pop();
                    renderTags(fieldName, containerId, state);
                }
            }
        }

        function removeTag(fieldName, containerId, index) {
            const state = getState(fieldName);
            state.splice(index, 1);
            renderTags(fieldName, containerId, state);
        }

        // ============================================
        // Dynamic array (other_languages)
        // ============================================
        let languageIndex = {{ count($existingLanguages) }};

        function addLanguage() {
            const container = document.getElementById('other-languages-container');

            // Cek limit max 5
            if (container.children.length >= 5) {
                alert('Maksimal 5 bahasa lain.');
                return;
            }

            const item = document.createElement('div');
            item.className = 'lingkup-array-item';
            item.innerHTML = `
                <input type="text" name="other_languages[${languageIndex}][lang]"
                    class="lingkup-form-control" placeholder="Bahasa (mis. Korean)" maxlength="50">
                <select name="other_languages[${languageIndex}][level]" class="lingkup-form-control">
                    <option value="">— Pilih tingkat —</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="advanced">Advanced</option>
                    <option value="native">Native</option>
                </select>
                <button type="button" class="lingkup-array-remove" onclick="removeArrayItem(this)" aria-label="Hapus">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            container.appendChild(item);
            languageIndex++;
        }

        function removeArrayItem(button) {
            button.closest('.lingkup-array-item').remove();
        }

        // ============================================
        // Character counter
        // ============================================
        function updateCharCounter(textarea, counterId) {
            const counter = document.getElementById(counterId);
            const length = textarea.value.length;
            const max = textarea.maxLength;
            counter.textContent = `${length} / ${max} karakter`;
            counter.classList.toggle('over-limit', length >= max);
        }

        // ============================================
        // Init on page load
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Render initial tags
            renderTags('current_skills', 'skills-tags-container', skillsState);
            renderTags('interests', 'interests-tags-container', interestsState);

            // Init char counters
            const orgTextarea = document.getElementById('organization_experience');
            const careerTextarea = document.getElementById('career_goal');
            if (orgTextarea) updateCharCounter(orgTextarea, 'org-counter');
            if (careerTextarea) updateCharCounter(careerTextarea, 'career-counter');

            // Scroll to first error (jika ada)
            const firstError = document.querySelector('.is-invalid, .lingkup-form-error');
            if (firstError) {
                setTimeout(() => {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
            }
        });
    </script>
@endsection