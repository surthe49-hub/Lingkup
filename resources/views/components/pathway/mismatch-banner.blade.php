@props(['mismatchInfo'])

@if ($mismatchInfo)
    <div class="pathway-mismatch-banner">
        <div class="mismatch-banner-icon">
            <i class="bi bi-exclamation-diamond-fill"></i>
        </div>

        <div class="mismatch-banner-content">
            <h3 class="mismatch-banner-title">Pathway Anda Tidak Sesuai dengan Target Saat Ini</h3>

            <p class="mismatch-banner-description">
                Anda memiliki pathway aktif untuk
                <strong>{{ $mismatchInfo['pathway_target']->name }}</strong>,
                namun target Anda saat ini adalah
                <strong>{{ $mismatchInfo['current_target']->name }}</strong>.
            </p>

            <div class="mismatch-banner-actions">
                <a href="{{ route('user.pathway.show', $mismatchInfo['pathway_id']) }}"
                   class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i>
                    Regenerate untuk {{ $mismatchInfo['current_target']->name }}
                </a>
                <a href="{{ route('user.pathway.show', $mismatchInfo['pathway_id']) }}"
                   class="btn btn-link btn-sm">
                    Lihat Pathway Lama
                </a>
            </div>

            <p class="mismatch-banner-help">
                <i class="bi bi-info-circle"></i>
                Pathway lama tetap dapat diakses sebagai history setelah regenerate.
            </p>
        </div>
    </div>
@endif