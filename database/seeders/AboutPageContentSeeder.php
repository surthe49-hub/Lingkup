<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class AboutPageContentSeeder extends Seeder
{
    /**
     * Migrasi 39 field konten statis about.blade.php ke database.
     * Semua konten di halaman About bersifat statis (tidak ada data
     * dinamis per-user seperti di Home), jadi tidak ada state khusus
     * yang dikecualikan.
     */
    public function run(): void
    {
        $contents = [
            // ===== HERO (4 field) =====
            ['section_key' => 'hero_badge', 'content' => 'About LINGKUP', 'content_type' => 'text'],
            ['section_key' => 'hero_title_prefix', 'content' => 'Membantu mahasiswa Indonesia menuju', 'content_type' => 'text'],
            ['section_key' => 'hero_title_highlight', 'content' => 'studi internasional', 'content_type' => 'text'],
            ['section_key' => 'hero_subtitle', 'content' => 'LINGKUP adalah platform persiapan studi luar negeri berbasis AI yang dirancang khusus untuk mahasiswa Indonesia dengan ambisi global.', 'content_type' => 'richtext'],

            // ===== MISI KAMI (12 field) =====
            ['section_key' => 'mission_eyebrow', 'content' => 'Misi Kami', 'content_type' => 'text'],
            ['section_key' => 'mission_title', 'content' => 'Memberdayakan akses pendidikan global', 'content_type' => 'text'],
            ['section_key' => 'mission_text_1', 'content' => 'Persiapan studi luar negeri sering kali terbatas pada mereka yang punya akses ke konsultan beasiswa premium atau jaringan alumni. LINGKUP hadir untuk menutup kesenjangan itu dengan menghadirkan panduan AI yang dapat diakses oleh siapa saja.', 'content_type' => 'richtext'],
            ['section_key' => 'mission_text_2', 'content' => 'Kami percaya bahwa setiap mahasiswa Indonesia berhak mendapatkan roadmap yang terstruktur, personal, dan dapat ditindaklanjuti, terlepas dari lokasi geografis atau latar belakang sosial-ekonomi.', 'content_type' => 'richtext'],
            ['section_key' => 'mission_stat1_value', 'content' => '8+', 'content_type' => 'text'],
            ['section_key' => 'mission_stat1_label', 'content' => 'Target beasiswa internasional', 'content_type' => 'text'],
            ['section_key' => 'mission_stat2_value', 'content' => 'AI', 'content_type' => 'text'],
            ['section_key' => 'mission_stat2_label', 'content' => 'Pathway personalisasi', 'content_type' => 'text'],
            ['section_key' => 'mission_stat3_value', 'content' => '100%', 'content_type' => 'text'],
            ['section_key' => 'mission_stat3_label', 'content' => 'Bahasa Indonesia', 'content_type' => 'text'],
            ['section_key' => 'mission_stat4_value', 'content' => 'Gratis', 'content_type' => 'text'],
            ['section_key' => 'mission_stat4_label', 'content' => 'Untuk semua mahasiswa', 'content_type' => 'text'],

            // ===== PRINSIP KAMI (8 field) =====
            ['section_key' => 'principles_eyebrow', 'content' => 'Prinsip Kami', 'content_type' => 'text'],
            ['section_key' => 'principles_title', 'content' => 'Yang membedakan LINGKUP', 'content_type' => 'text'],
            ['section_key' => 'principle1_title', 'content' => 'Personal, bukan generik', 'content_type' => 'text'],
            ['section_key' => 'principle1_text', 'content' => 'Setiap pathway dibuat berdasarkan profil akademik, kemampuan bahasa, dan target studi unik dari masing-masing pengguna — bukan template yang sama untuk semua.', 'content_type' => 'richtext'],
            ['section_key' => 'principle2_title', 'content' => 'Terstruktur dan bertahap', 'content_type' => 'text'],
            ['section_key' => 'principle2_text', 'content' => 'Persiapan beasiswa dibagi ke dalam fase-fase yang jelas dengan task konkret di setiap fase. Pengguna tidak perlu kewalahan memikirkan semua hal sekaligus.', 'content_type' => 'richtext'],
            ['section_key' => 'principle3_title', 'content' => 'Konteks lokal Indonesia', 'content_type' => 'text'],
            ['section_key' => 'principle3_text', 'content' => 'Output AI disesuaikan dengan realitas mahasiswa Indonesia: kalender akademik, deadline LPDP, persiapan IELTS, hingga referensi institusi dalam negeri.', 'content_type' => 'richtext'],

            // ===== CERITA DI BALIK LINGKUP (11 field) =====
            ['section_key' => 'story_eyebrow', 'content' => 'Cerita di Balik LINGKUP', 'content_type' => 'text'],
            ['section_key' => 'story_title', 'content' => 'Dibangun sebagai proyek penelitian Design Science Research', 'content_type' => 'text'],
            ['section_key' => 'story_text_1', 'content' => 'LINGKUP merupakan implementasi dari penelitian skripsi yang berfokus pada penerapan teknologi AI generatif untuk mendukung perjalanan akademik mahasiswa Indonesia. Metodologi Design Science Research digunakan untuk memastikan setiap iterasi platform dievaluasi secara sistematis berdasarkan kebutuhan nyata pengguna.', 'content_type' => 'richtext'],
            ['section_key' => 'story_text_2', 'content' => 'Platform ini dikembangkan oleh <strong>Muhammad Rafi Awallaisal</strong>, mahasiswa Sistem Informasi Telkom University Purwokerto, sebagai kontribusi nyata bagi komunitas akademik Indonesia. Setiap fitur dibangun dengan mempertimbangkan keterbatasan akses yang sering dihadapi mahasiswa di luar pusat-pusat kota besar.', 'content_type' => 'richtext'],
            ['section_key' => 'story_tech_heading', 'content' => 'Stack teknologi:', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_1', 'content' => 'Laravel 12', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_2', 'content' => 'PHP 8.3', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_3', 'content' => 'MySQL', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_4', 'content' => 'Bootstrap 5', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_5', 'content' => 'Vite', 'content_type' => 'text'],
            ['section_key' => 'story_tech_badge_6', 'content' => 'Gemini AI', 'content_type' => 'text'],

            // ===== FINAL CTA (3 field) =====
            ['section_key' => 'cta_title', 'content' => 'Siap memulai perjalananmu?', 'content_type' => 'text'],
            ['section_key' => 'cta_subtitle', 'content' => 'Lanjutkan ke dashboard dan dapatkan pathway AI personal untuk target studimu.', 'content_type' => 'richtext'],
            ['section_key' => 'cta_button', 'content' => 'Masuk ke Dashboard', 'content_type' => 'text'],

            // ===== FOOTER (1 field) =====
            ['section_key' => 'footer_tagline', 'content' => 'Your Global Pathway Starts Here', 'content_type' => 'text'],
        ];

        foreach ($contents as $item) {
            PageContent::updateOrCreate(
                ['page' => 'about', 'section_key' => $item['section_key']],
                ['content' => $item['content'], 'content_type' => $item['content_type']]
            );
        }
    }
}
