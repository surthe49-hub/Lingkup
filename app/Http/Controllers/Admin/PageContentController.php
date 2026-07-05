<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageContentController extends Controller
{
    /**
     * Daftar section_key per halaman, dikelompokkan per section untuk
     * tampilan form, plus label yang human-readable dan tipe widget-nya.
     *
     * Menambah halaman baru (Home, About) di masa depan = tinggal
     * tambah entry array di sini, tidak perlu ubah method lain.
     */
    private const PAGE_SCHEMAS = [
        'landing' => [
            'label' => 'Landing Page',
            'groups' => [
                'Hero' => [
                    'hero_badge' => ['label' => 'Badge Kecil', 'type' => 'text'],
                    'hero_title_prefix' => ['label' => 'Judul — Bagian 1', 'type' => 'text'],
                    'hero_title_highlight' => ['label' => 'Judul — Bagian 2 (Highlight Warna)', 'type' => 'text'],
                    'hero_title_suffix' => ['label' => 'Judul — Bagian 3', 'type' => 'text'],
                    'hero_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                    'hero_cta_primary' => ['label' => 'Teks Tombol Utama', 'type' => 'text'],
                    'hero_cta_secondary' => ['label' => 'Teks Tombol Kedua', 'type' => 'text'],
                    'hero_trust_text' => ['label' => 'Trust Text (di bawah tombol)', 'type' => 'text'],
                ],
                'Cara Kerja' => [
                    'how_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'how_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'how_subtitle' => ['label' => 'Subtitle Section', 'type' => 'richtext'],
                    'step1_title' => ['label' => 'Step 1 — Judul', 'type' => 'text'],
                    'step1_desc' => ['label' => 'Step 1 — Deskripsi', 'type' => 'richtext'],
                    'step2_title' => ['label' => 'Step 2 — Judul', 'type' => 'text'],
                    'step2_desc' => ['label' => 'Step 2 — Deskripsi', 'type' => 'richtext'],
                    'step3_title' => ['label' => 'Step 3 — Judul', 'type' => 'text'],
                    'step3_desc' => ['label' => 'Step 3 — Deskripsi', 'type' => 'richtext'],
                ],
                'Value Propositions' => [
                    'value_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'value_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'value1_title' => ['label' => 'Value 1 — Judul', 'type' => 'text'],
                    'value1_desc' => ['label' => 'Value 1 — Deskripsi', 'type' => 'richtext'],
                    'value2_title' => ['label' => 'Value 2 — Judul', 'type' => 'text'],
                    'value2_desc' => ['label' => 'Value 2 — Deskripsi', 'type' => 'richtext'],
                    'value3_title' => ['label' => 'Value 3 — Judul', 'type' => 'text'],
                    'value3_desc' => ['label' => 'Value 3 — Deskripsi', 'type' => 'richtext'],
                    'value4_title' => ['label' => 'Value 4 — Judul', 'type' => 'text'],
                    'value4_desc' => ['label' => 'Value 4 — Deskripsi', 'type' => 'richtext'],
                ],
                'Final CTA' => [
                    'cta_title' => ['label' => 'Judul', 'type' => 'text'],
                    'cta_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                    'cta_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Footer' => [
                    'footer_tagline' => ['label' => 'Tagline', 'type' => 'text'],
                ],
            ],
        ],

        'home' => [
            'label' => 'Home Page (Post-Login)',
            'groups' => [
                'Hero — Need Profile' => [
                    'need_profile_hero_title' => ['label' => 'Judul — Bagian 1', 'type' => 'text'],
                    'need_profile_hero_title_highlight' => ['label' => 'Judul — Bagian 2 (Highlight)', 'type' => 'text'],
                    'need_profile_hero_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                ],
                'Hero — Need Target' => [
                    'need_target_hero_title' => ['label' => 'Judul — Bagian 1', 'type' => 'text'],
                    'need_target_hero_title_highlight' => ['label' => 'Judul — Bagian 2 (Highlight)', 'type' => 'text'],
                    'need_target_hero_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                ],
                'Hero — Ready Generate' => [
                    'ready_generate_hero_title_prefix' => ['label' => 'Judul — Sebelum Nama Target', 'type' => 'text'],
                    'ready_generate_hero_title_suffix' => ['label' => 'Judul — Setelah Nama Target', 'type' => 'text'],
                    'ready_generate_hero_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                ],
                'Hero — Tombol (Semua State)' => [
                    'hero_dashboard_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Journey Card — Need Profile' => [
                    'need_profile_journey_title' => ['label' => 'Judul', 'type' => 'text'],
                    'need_profile_journey_desc' => ['label' => 'Deskripsi', 'type' => 'richtext'],
                    'need_profile_journey_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Journey Card — Need Target' => [
                    'need_target_journey_title' => ['label' => 'Judul', 'type' => 'text'],
                    'need_target_journey_desc' => ['label' => 'Deskripsi', 'type' => 'richtext'],
                    'need_target_journey_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Journey Card — Ready Generate' => [
                    'ready_generate_journey_title' => ['label' => 'Judul', 'type' => 'text'],
                    'ready_generate_journey_desc_prefix' => ['label' => 'Deskripsi — Sebelum Nama Target', 'type' => 'text'],
                    'ready_generate_journey_desc_suffix' => ['label' => 'Deskripsi — Setelah Nama Target', 'type' => 'richtext'],
                    'ready_generate_journey_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Section Negara — Header' => [
                    'countries_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'countries_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'countries_subtitle' => ['label' => 'Subtitle Section', 'type' => 'richtext'],
                    'countries_footer_button' => ['label' => 'Teks Tombol "Lihat Semua"', 'type' => 'text'],
                ],
                'Final CTA' => [
                    'cta_default_title' => ['label' => 'Judul (versi belum ada pathway aktif)', 'type' => 'text'],
                    'cta_default_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                    'cta_dashboard_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Footer' => [
                    'footer_tagline' => ['label' => 'Tagline', 'type' => 'text'],
                ],
            ],
        ],

        'about' => [
            'label' => 'About Page',
            'groups' => [
                'Hero' => [
                    'hero_badge' => ['label' => 'Badge Kecil', 'type' => 'text'],
                    'hero_title_prefix' => ['label' => 'Judul — Bagian 1', 'type' => 'text'],
                    'hero_title_highlight' => ['label' => 'Judul — Bagian 2 (Highlight)', 'type' => 'text'],
                    'hero_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                ],
                'Misi Kami' => [
                    'mission_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'mission_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'mission_text_1' => ['label' => 'Paragraf 1', 'type' => 'richtext'],
                    'mission_text_2' => ['label' => 'Paragraf 2', 'type' => 'richtext'],
                    'mission_stat1_value' => ['label' => 'Statistik 1 — Angka', 'type' => 'text'],
                    'mission_stat1_label' => ['label' => 'Statistik 1 — Label', 'type' => 'text'],
                    'mission_stat2_value' => ['label' => 'Statistik 2 — Angka', 'type' => 'text'],
                    'mission_stat2_label' => ['label' => 'Statistik 2 — Label', 'type' => 'text'],
                    'mission_stat3_value' => ['label' => 'Statistik 3 — Angka', 'type' => 'text'],
                    'mission_stat3_label' => ['label' => 'Statistik 3 — Label', 'type' => 'text'],
                    'mission_stat4_value' => ['label' => 'Statistik 4 — Angka', 'type' => 'text'],
                    'mission_stat4_label' => ['label' => 'Statistik 4 — Label', 'type' => 'text'],
                ],
                'Prinsip Kami' => [
                    'principles_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'principles_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'principle1_title' => ['label' => 'Prinsip 1 — Judul', 'type' => 'text'],
                    'principle1_text' => ['label' => 'Prinsip 1 — Deskripsi', 'type' => 'richtext'],
                    'principle2_title' => ['label' => 'Prinsip 2 — Judul', 'type' => 'text'],
                    'principle2_text' => ['label' => 'Prinsip 2 — Deskripsi', 'type' => 'richtext'],
                    'principle3_title' => ['label' => 'Prinsip 3 — Judul', 'type' => 'text'],
                    'principle3_text' => ['label' => 'Prinsip 3 — Deskripsi', 'type' => 'richtext'],
                ],
                'Cerita di Balik LINGKUP' => [
                    'story_eyebrow' => ['label' => 'Eyebrow / Label Kecil', 'type' => 'text'],
                    'story_title' => ['label' => 'Judul Section', 'type' => 'text'],
                    'story_text_1' => ['label' => 'Paragraf 1 (Metodologi)', 'type' => 'richtext'],
                    'story_text_2' => ['label' => 'Paragraf 2 (Tentang Penulis)', 'type' => 'richtext'],
                    'story_tech_heading' => ['label' => 'Judul "Stack Teknologi"', 'type' => 'text'],
                    'story_tech_badge_1' => ['label' => 'Tech Badge 1', 'type' => 'text'],
                    'story_tech_badge_2' => ['label' => 'Tech Badge 2', 'type' => 'text'],
                    'story_tech_badge_3' => ['label' => 'Tech Badge 3', 'type' => 'text'],
                    'story_tech_badge_4' => ['label' => 'Tech Badge 4', 'type' => 'text'],
                    'story_tech_badge_5' => ['label' => 'Tech Badge 5', 'type' => 'text'],
                    'story_tech_badge_6' => ['label' => 'Tech Badge 6', 'type' => 'text'],
                ],
                'Final CTA' => [
                    'cta_title' => ['label' => 'Judul', 'type' => 'text'],
                    'cta_subtitle' => ['label' => 'Subtitle', 'type' => 'richtext'],
                    'cta_button' => ['label' => 'Teks Tombol', 'type' => 'text'],
                ],
                'Footer' => [
                    'footer_tagline' => ['label' => 'Tagline', 'type' => 'text'],
                ],
            ],
        ],
    ];

    /**
     * Tag HTML yang diizinkan untuk field richtext (WYSIWYG).
     * Semua tag lain akan dibuang saat disimpan.
     */
    private const ALLOWED_RICHTEXT_TAGS = '<b><strong><i><em><u><a>';

    /**
     * GET /admin/pages/{page}/edit
     */
    public function edit(string $page): View
    {
        abort_unless(isset(self::PAGE_SCHEMAS[$page]), 404);

        $schema = self::PAGE_SCHEMAS[$page];
        $existingContent = PageContent::forPage($page)->pluck('content', 'section_key');

        return view('admin.page-contents.edit', [
            'page' => $page,
            'pageLabel' => $schema['label'],
            'groups' => $schema['groups'],
            'existingContent' => $existingContent,
        ]);
    }

    /**
     * PATCH /admin/pages/{page}
     */
    public function update(Request $request, string $page): RedirectResponse
    {
        abort_unless(isset(self::PAGE_SCHEMAS[$page]), 404);

        $schema = self::PAGE_SCHEMAS[$page];

        // Flatten semua section_key dari semua group jadi 1 array untuk validasi
        $allFields = collect($schema['groups'])->flatMap(fn ($fields) => $fields);

        $rules = $allFields->mapWithKeys(function ($field, $key) {
            return [$key => ['nullable', 'string']];
        })->toArray();

        $validated = $request->validate($rules);

        foreach ($allFields as $sectionKey => $fieldMeta) {
            $value = $validated[$sectionKey] ?? '';

            // Sanitasi HTML untuk field richtext — hanya izinkan tag aman
            if ($fieldMeta['type'] === 'richtext') {
                $value = strip_tags($value, self::ALLOWED_RICHTEXT_TAGS);
            } else {
                // Field text biasa: buang SEMUA tag HTML sama sekali
                $value = strip_tags($value);
            }

            PageContent::updateOrCreate(
                ['page' => $page, 'section_key' => $sectionKey],
                ['content' => $value, 'content_type' => $fieldMeta['type']]
            );
        }

        // WAJIB: clear cache supaya perubahan langsung muncul di halaman publik
        PageContent::clearCache($page);

        return redirect()
            ->route('admin.page-contents.edit', $page)
            ->with('success', "Konten {$schema['label']} berhasil diperbarui.");
    }
}