/**
 * Pathway Generation Handler (Phase 4)
 *
 * AJAX flow:
 * 1. User klik tombol Generate
 * 2. Loading overlay tampil
 * 3. POST /pathway/generate
 * 4. Loading state update setiap detik
 * 5. On success → redirect ke view_url
 * 6. On error → tampilkan error state
 */
(function () {
    'use strict';

    const generateBtn = document.getElementById('generate-pathway-btn');
    const loadingOverlay = document.getElementById('pathway-loading-overlay');
    const errorState = document.getElementById('pathway-error-state');
    const elapsedEl = document.getElementById('loading-elapsed');
    const loadingTitle = document.getElementById('loading-title');
    const loadingSubtitle = document.getElementById('loading-subtitle');
    const errorMessage = document.getElementById('error-message');

    if (!generateBtn) {
        return;
    }

    const loadingMessages = [
        {
            title: 'Sedang menyusun roadmap untuk Anda...',
            subtitle: 'AI sedang menganalisis profil dan target Anda. Mohon tunggu sebentar.',
        },
        {
            title: 'Mengidentifikasi gap dan strategi...',
            subtitle: 'AI sedang memetakan kebutuhan persiapan dari profil Anda ke persyaratan target.',
        },
        {
            title: 'Menyusun fase persiapan...',
            subtitle: 'Roadmap dibagi menjadi fase-fase logis berdasarkan timeline realistis.',
        },
        {
            title: 'Memfinalisasi task konkret...',
            subtitle: 'Setiap fase berisi task actionable yang dapat Anda mulai segera.',
        },
        {
            title: 'Hampir selesai...',
            subtitle: 'Validasi output dan persiapan untuk ditampilkan kepada Anda.',
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

    function showLoading() {
        loadingOverlay.style.display = 'flex';
        generateBtn.disabled = true;
        elapsedSeconds = 0;

        timerInterval = setInterval(() => {
            elapsedSeconds++;
            elapsedEl.textContent = elapsedSeconds;
        }, 1000);

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
        generateBtn.disabled = false;
        if (timerInterval) clearInterval(timerInterval);
        if (messageInterval) clearInterval(messageInterval);
    }

    function showError(message) {
        hideLoading();
        errorState.style.display = 'block';
        errorMessage.textContent = message || 'Terjadi kesalahan. Silakan coba lagi.';
        errorState.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    async function generatePathway() {
        showLoading();
        errorState.style.display = 'none';

        try {
            const response = await fetch('/pathway/generate', {
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
                loadingTitle.textContent = 'Pathway berhasil dibuat!';
                loadingSubtitle.textContent = 'Mengarahkan Anda ke halaman detail...';
                setTimeout(() => {
                    window.location.href = data.view_url;
                }, 1500);
            } else {
                showError(data.message || 'Generation gagal. Silakan coba lagi.');
            }
        } catch (error) {
            console.error('Pathway generation error:', error);
            showError('Tidak dapat terhubung ke server. Periksa koneksi internet Anda.');
        }
    }

    generateBtn.addEventListener('click', generatePathway);
})();