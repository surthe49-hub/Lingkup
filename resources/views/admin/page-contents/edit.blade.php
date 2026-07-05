@extends('layouts.dashboard')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">

<div class="lingkup-page-header">
    <h1 class="lingkup-page-title">Edit Konten: {{ $pageLabel }}</h1>
    <p class="lingkup-page-subtitle">
        Perubahan akan langsung tampil di halaman publik setelah disimpan. Klik judul section untuk buka/tutup.
    </p>
</div>

<form method="POST" action="{{ route('admin.page-contents.update', $page) }}" id="pageContentForm">
    @csrf
    @method('PATCH')

    @foreach ($groups as $groupName => $fields)
        @php $groupSlug = \Illuminate\Support\Str::slug($groupName); @endphp

        <div class="lingkup-card lingkup-page-content-group">
            <button type="button"
                    class="lingkup-page-content-group-header"
                    data-bs-toggle="collapse"
                    data-bs-target="#group-{{ $groupSlug }}"
                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                <h2 class="lingkup-page-content-group-title">{{ $groupName }}</h2>
                <span class="lingkup-page-content-group-meta">
                    {{ count($fields) }} field
                    <i class="bi bi-chevron-down"></i>
                </span>
            </button>

            <div id="group-{{ $groupSlug }}" class="collapse {{ $loop->first ? 'show' : '' }}">
                <div class="lingkup-page-content-fields-grid">
                    @foreach ($fields as $sectionKey => $fieldMeta)
                        <div class="lingkup-form-group {{ $fieldMeta['type'] === 'richtext' ? 'lingkup-form-group-full' : '' }}">
                            <label for="field-{{ $sectionKey }}" class="lingkup-form-label">
                                {{ $fieldMeta['label'] }}
                            </label>

                            @if ($fieldMeta['type'] === 'richtext')
                                <div id="quill-{{ $sectionKey }}"
                                     class="lingkup-quill-editor @error($sectionKey) lingkup-form-input-error @enderror">{!! old($sectionKey, $existingContent[$sectionKey] ?? '') !!}</div>
                                <textarea name="{{ $sectionKey }}"
                                          id="field-{{ $sectionKey }}"
                                          class="lingkup-quill-hidden-input"
                                          style="display: none;">{{ old($sectionKey, $existingContent[$sectionKey] ?? '') }}</textarea>
                            @else
                                <input type="text"
                                       name="{{ $sectionKey }}"
                                       id="field-{{ $sectionKey }}"
                                       value="{{ old($sectionKey, $existingContent[$sectionKey] ?? '') }}"
                                       class="lingkup-form-input @error($sectionKey) lingkup-form-input-error @enderror">
                            @endif

                            @error($sectionKey)
                                <span class="lingkup-form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div class="lingkup-form-actions lingkup-page-content-submit">
        <button type="submit" class="btn btn-primary btn-lg">Simpan Semua Perubahan</button>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toolbarOptions = [['bold', 'italic', 'underline', 'link']];
    const quillInstances = {};

    document.querySelectorAll('.lingkup-quill-editor').forEach(function (editorEl) {
        const sectionKey = editorEl.id.replace('quill-', '');
        const hiddenInput = document.getElementById('field-' + sectionKey);

        const quill = new Quill(editorEl, {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
        });

        quillInstances[sectionKey] = quill;

        quill.on('text-change', function () {
            hiddenInput.value = quill.root.innerHTML;
        });
    });

    // Rotate ikon chevron sesuai state collapse tiap group
    document.querySelectorAll('.lingkup-page-content-group-header').forEach(function (header) {
        const targetId = header.dataset.bsTarget;
        const collapseEl = document.querySelector(targetId);
        if (! collapseEl) return;

        collapseEl.addEventListener('show.bs.collapse', function () {
            header.setAttribute('aria-expanded', 'true');
        });
        collapseEl.addEventListener('hide.bs.collapse', function () {
            header.setAttribute('aria-expanded', 'false');
        });
    });

    document.getElementById('pageContentForm').addEventListener('submit', function () {
        Object.keys(quillInstances).forEach(function (sectionKey) {
            const hiddenInput = document.getElementById('field-' + sectionKey);
            hiddenInput.value = quillInstances[sectionKey].root.innerHTML;
        });
    });
});
</script>
@endpush
@endsection