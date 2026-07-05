<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class HomePageContentSeeder extends Seeder
{
    /**
     * Migrasi 28 field konten statis home.blade.php ke database.
     *
     * TIDAK termasuk state 'active' (Hero + Journey Card) — itu
     * sengaja dikecualikan karena isinya dominan data pathway asli
     * user (judul pathway, ringkasan, statistik), bukan konten
     * marketing statis. Lihat diskusi sebelumnya.
     */
    public function run(): void
    {
        $contents = [
            // ===== HERO — Need Profile (3 field) =====
            ['section_key' => 'need_profile_hero_title', 'content' => 'Mari mulai perjalanan menuju', 'content_type' => 'text'],
            ['section_key' => 'need_profile_hero_title_highlight', 'content' => 'studi internasional', 'content_type' => 'text'],
            ['section_key' => 'need_profile_hero_subtitle', 'content' => 'Lengkapi profil akademikmu untuk memulai pengalaman LINGKUP.', 'content_type' => 'richtext'],

            // ===== HERO — Need Target (3 field) =====
            ['section_key' => 'need_target_hero_title', 'content' => 'Mari pilih', 'content_type' => 'text'],
            ['section_key' => 'need_target_hero_title_highlight', 'content' => 'target studimu', 'content_type' => 'text'],
            ['section_key' => 'need_target_hero_subtitle', 'content' => 'Profilmu sudah lengkap. Langkah berikutnya adalah memilih beasiswa atau program internasional yang ingin kamu kejar.', 'content_type' => 'richtext'],

            // ===== HERO — Ready Generate (3 field) =====
            ['section_key' => 'ready_generate_hero_title_prefix', 'content' => 'Siap menyusun roadmap', 'content_type' => 'text'],
            ['section_key' => 'ready_generate_hero_title_suffix', 'content' => '?', 'content_type' => 'text'],
            ['section_key' => 'ready_generate_hero_subtitle', 'content' => 'Profil dan targetmu sudah lengkap. Saatnya AI menyusun roadmap personal untukmu.', 'content_type' => 'richtext'],

            // ===== HERO — Tombol (semua state, 1 field) =====
            ['section_key' => 'hero_dashboard_button', 'content' => 'Masuk ke Dashboard', 'content_type' => 'text'],

            // ===== JOURNEY CARD — Need Profile (3 field) =====
            ['section_key' => 'need_profile_journey_title', 'content' => 'Lengkapi profil akademikmu', 'content_type' => 'text'],
            ['section_key' => 'need_profile_journey_desc', 'content' => 'Isi data jurusan, semester, IPK, dan kemampuan bahasa. Profil membantu AI memahami konteks unik perjalananmu.', 'content_type' => 'richtext'],
            ['section_key' => 'need_profile_journey_button', 'content' => 'Mulai Lengkapi Profil', 'content_type' => 'text'],

            // ===== JOURNEY CARD — Need Target (3 field) =====
            ['section_key' => 'need_target_journey_title', 'content' => 'Pilih target studimu', 'content_type' => 'text'],
            ['section_key' => 'need_target_journey_desc', 'content' => 'Pilih beasiswa atau program internasional dari 8+ pilihan seperti Chevening, MEXT, AAS, Fulbright, dan lainnya.', 'content_type' => 'richtext'],
            ['section_key' => 'need_target_journey_button', 'content' => 'Pilih Target Sekarang', 'content_type' => 'text'],

            // ===== JOURNEY CARD — Ready Generate (4 field) =====
            ['section_key' => 'ready_generate_journey_title', 'content' => 'Pathway-mu menunggu disusun', 'content_type' => 'text'],
            ['section_key' => 'ready_generate_journey_desc_prefix', 'content' => 'Target', 'content_type' => 'text'],
            ['section_key' => 'ready_generate_journey_desc_suffix', 'content' => 'sudah dipilih. AI akan menyusun roadmap multi-fase yang disesuaikan dengan profilmu.', 'content_type' => 'richtext'],
            ['section_key' => 'ready_generate_journey_button', 'content' => 'Mulai Generate Pathway', 'content_type' => 'text'],

            // ===== SECTION NEGARA — Header (4 field) =====
            ['section_key' => 'countries_eyebrow', 'content' => 'Inspirasi · Negara Tujuan', 'content_type' => 'text'],
            ['section_key' => 'countries_title', 'content' => 'Negara impianmu menanti', 'content_type' => 'text'],
            ['section_key' => 'countries_subtitle', 'content' => 'Pilih destinasi studi internasional yang sesuai dengan visi karier dan minat akademikmu.', 'content_type' => 'richtext'],
            ['section_key' => 'countries_footer_button', 'content' => 'Lihat Semua Target Studi', 'content_type' => 'text'],

            // ===== FINAL CTA — Versi Default/Non-Active (3 field) =====
            ['section_key' => 'cta_default_title', 'content' => 'Akses semua fitur di dashboard', 'content_type' => 'text'],
            ['section_key' => 'cta_default_subtitle', 'content' => 'Dashboard berisi semua tool yang kamu butuhkan untuk persiapan studimu.', 'content_type' => 'richtext'],
            ['section_key' => 'cta_dashboard_button', 'content' => 'Masuk ke Dashboard', 'content_type' => 'text'],

            // ===== FOOTER (1 field) =====
            ['section_key' => 'footer_tagline', 'content' => 'Your Global Pathway Starts Here', 'content_type' => 'text'],
        ];

        foreach ($contents as $item) {
            PageContent::updateOrCreate(
                ['page' => 'home', 'section_key' => $item['section_key']],
                ['content' => $item['content'], 'content_type' => $item['content_type']]
            );
        }
    }
}
