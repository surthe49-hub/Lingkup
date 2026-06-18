/**
 * Pathway Regeneration Handler (Phase 5.2)
 *
 * AJAX flow:
 * 1. User klik "Ya, Regenerate" di modal
 * 2. Modal close, loading overlay tampil
 * 3. POST /pathway/regenerate
 * 4. On success → redirect ke view_url
 * 5. On error → tampilkan error state
 */
(function () {
    'use strict';

    const confirmBtn = document.getElementById('confirm-regenerate-btn');
    const loadingOverlay = document.getElementById('pathway-regenerate-overlay');
    const errorState = document.getElementById('pathway-regenerate-error');
    const elapsedEl = document.getElementById('regenerate-loading-elapsed');
    const loadingTitle = document.getElementById('regenerate-loading-title');
    const loadingSubtitle = document.getElementById('regenerate-loading-subtitle');
    const errorMessage = document.getElementById('regenerate-error-message');
    const modalEl = document.getElementById('regenerateConfirmModal');

    if (!confirmBtn) {
        // Tidak di halaman pathway dengan regenerate (e.g., archived state)
        return;
    }

    // Loading messages yang rotate setiap 5 detik
    const loadingMessages = [
        {
            title: 'Sedang me-regenerate pathway...',
            subtitle: 'AI sedang menganalisis ulang profil dan target Anda.',
        },
        {
            title: 'Menyusun strategi baru...',
            subtitle: 'Mengidentifikasi pendekatan alternatif untuk roadmap Anda.',
        },
        {
            title: 'Memetakan fase persiapan...',
            subtitle: 'Membagi roadmap ke dalam fase-fase logis dengan timeline realistis.',
        },
        {
            title: 'Menghasilkan task konkret...',
            subtitle: 'Setiap fase diisi dengan task actionable yang dapat langsung dijalankan.',
        },
        {
            title: 'Hampir selesai...',
            subtitle: 'Validasi output dan persiapan untuk ditampilkan.',
        },
    ];

    let elapsedSeconds = 0;
    let timerInterval = null;
    let messageInterval = null;

    function getCsrfToken() {
        const tokenCookie = document.cookie
            .split('; ')
            .find(row => row.startsWith('XSRF-TOKEN='));
        return tokenCookie ? decodeURIComponent(tokenCookie.split('=')[1]) : '';
    }

    function closeModal() {
        // Trigger Bootstrap modal hide via instance
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        }
    }

    function showLoading() {
        loadingOverlay.style.display = 'flex';
        elapsedSeconds = 0;

        // Timer per detik
        timerInterval = setInterval(() => {
            elapsedSeconds++;
            elapsedEl.textContent = elapsedSeconds;
        }, 1000);

        // Rotate message setiap 5 detik
        let messageIndex = 0;
        messageInterval = setInterval(() => {
            messageIndex = (messageIndex + 1) % loadingMessages.length;
            const msg = loadingMessages[messageIndex];
            loadingTitle.textContent = msg.title;
            loadingSubtitle.textContent = msg.subtitle;
        }, 5000);
    }

    function hideLoading() {
        loadingOverlay.style.display = 'none';
        if (timerInterval) clearInterval(timerInterval);
        if (messageInterval) clearInterval(messageInterval);
    }

    function showError(message) {
        hideLoading();
        errorState.style.display = 'block';
        errorMessage.textContent = message || 'Terjadi kesalahan. Silakan coba lagi.';
        errorState.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function regeneratePathway() {
        closeModal();
        showLoading();
        errorState.style.display = 'none';

        try {
            const response = await fetch('/pathway/regenerate', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': getCsrfToken(),
                },
            });

            const data = await response.json();

            if (data.success && data.view_url) {
                // Success: redirect ke detail page baru
                loadingTitle.textContent = 'Pathway berhasil di-regenerate!';
                loadingSubtitle.textContent = 'Mengarahkan Anda ke pathway baru...';
                setTimeout(() => {
                    window.location.href = data.view_url;
                }, 1500);
            } else {
                // Error response
                showError(data.message || 'Regeneration gagal. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Pathway regeneration error:', error);
            showError('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        }
    }

    confirmBtn.addEventListener('click', regeneratePathway);
})();