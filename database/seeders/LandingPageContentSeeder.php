<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class LandingPageContentSeeder extends Seeder
{
    /**
     * Migrasi 31 field konten landing page yang sebelumnya hardcoded
     * di landing.blade.php ke database, supaya tampilan TIDAK berubah
     * sama sekali pada saat pertama kali fitur ini di-deploy.
     */
    public function run(): void
    {
        $contents = [
            // ===== HERO (8 field) =====
            ['section_key' => 'hero_badge', 'content' => 'AI-Powered Career Navigator', 'content_type' => 'text'],
            ['section_key' => 'hero_title_prefix', 'content' => 'Roadmap personal AI untuk', 'content_type' => 'text'],
            ['section_key' => 'hero_title_highlight', 'content' => 'studi internasional', 'content_type' => 'text'],
            ['section_key' => 'hero_title_suffix', 'content' => 'impianmu.', 'content_type' => 'text'],
            ['section_key' => 'hero_subtitle', 'content' => 'LINGKUP membantu mahasiswa Indonesia menyusun langkah persiapan beasiswa dan studi luar negeri dengan AI yang memahami profilmu.', 'content_type' => 'richtext'],
            ['section_key' => 'hero_cta_primary', 'content' => 'Mulai Gratis Sekarang', 'content_type' => 'text'],
            ['section_key' => 'hero_cta_secondary', 'content' => 'Sudah punya akun?', 'content_type' => 'text'],
            ['section_key' => 'hero_trust_text', 'content' => 'Gratis untuk mahasiswa Indonesia · Tidak perlu kartu kredit', 'content_type' => 'text'],

            // ===== HOW IT WORKS (9 field) =====
            ['section_key' => 'how_eyebrow', 'content' => 'Cara Kerja', 'content_type' => 'text'],
            ['section_key' => 'how_title', 'content' => 'Tiga langkah menuju pathway personalmu', 'content_type' => 'text'],
            ['section_key' => 'how_subtitle', 'content' => 'Sederhana, terstruktur, dan dipandu AI sepanjang prosesnya.', 'content_type' => 'richtext'],
            ['section_key' => 'step1_title', 'content' => 'Lengkapi Profil Akademik', 'content_type' => 'text'],
            ['section_key' => 'step1_desc', 'content' => 'Isi data jurusan, semester, IPK, kemampuan bahasa, dan minat karier untuk membantu AI memahami konteks unikmu.', 'content_type' => 'richtext'],
            ['section_key' => 'step2_title', 'content' => 'Pilih Target Studi', 'content_type' => 'text'],
            ['section_key' => 'step2_desc', 'content' => 'Tentukan beasiswa atau program internasional yang ingin kamu kejar dari 8+ pilihan: MEXT, LPDP, Chevening, AAS, dan lainnya.', 'content_type' => 'richtext'],
            ['section_key' => 'step3_title', 'content' => 'Dapatkan Pathway AI', 'content_type' => 'text'],
            ['section_key' => 'step3_desc', 'content' => 'AI akan menyusun roadmap multi-fase dengan task konkret, estimasi durasi, dan prioritas yang disesuaikan denganmu.', 'content_type' => 'richtext'],

            // ===== VALUE PROPOSITIONS (10 field) =====
            ['section_key' => 'value_eyebrow', 'content' => 'Mengapa LINGKUP', 'content_type' => 'text'],
            ['section_key' => 'value_title', 'content' => 'Dirancang khusus untuk mahasiswa Indonesia', 'content_type' => 'text'],
            ['section_key' => 'value1_title', 'content' => 'Pathway AI Personalisasi', 'content_type' => 'text'],
            ['section_key' => 'value1_desc', 'content' => 'Setiap roadmap dibuat unik berdasarkan profil akademik, bahasa, dan target studimu — bukan template generik.', 'content_type' => 'richtext'],
            ['section_key' => 'value2_title', 'content' => '8+ Target Beasiswa', 'content_type' => 'text'],
            ['section_key' => 'value2_desc', 'content' => 'MEXT (Jepang), Chevening (UK), AAS (Australia), Fulbright (US), DAAD (Jerman), GKS (Korea), Erasmus+ (Eropa), LPDP.', 'content_type' => 'richtext'],
            ['section_key' => 'value3_title', 'content' => 'Bahasa Indonesia', 'content_type' => 'text'],
            ['section_key' => 'value3_desc', 'content' => 'Antarmuka dan output AI sepenuhnya berbahasa Indonesia. Lebih mudah dipahami dan terasa lebih dekat.', 'content_type' => 'richtext'],
            ['section_key' => 'value4_title', 'content' => 'Gratis Sepenuhnya', 'content_type' => 'text'],
            ['section_key' => 'value4_desc', 'content' => 'Dibangun untuk membantu mahasiswa Indonesia berani melangkah ke kancah internasional — tanpa biaya berlangganan.', 'content_type' => 'richtext'],

            // ===== FINAL CTA (3 field) =====
            ['section_key' => 'cta_title', 'content' => 'Siap memulai perjalananmu?', 'content_type' => 'text'],
            ['section_key' => 'cta_subtitle', 'content' => 'Bergabung dengan LINGKUP hari ini dan dapatkan pathway AI personalmu.', 'content_type' => 'richtext'],
            ['section_key' => 'cta_button', 'content' => 'Daftar Sekarang', 'content_type' => 'text'],

            // ===== FOOTER (1 field) =====
            ['section_key' => 'footer_tagline', 'content' => 'Your Global Pathway Starts Here', 'content_type' => 'text'],
        ];

        foreach ($contents as $item) {
            PageContent::updateOrCreate(
                ['page' => 'landing', 'section_key' => $item['section_key']],
                ['content' => $item['content'], 'content_type' => $item['content_type']]
            );
        }
    }
}
