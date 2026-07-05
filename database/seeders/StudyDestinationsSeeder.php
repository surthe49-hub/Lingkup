<?php

namespace Database\Seeders;

use App\Models\StudyDestination;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class StudyDestinationsSeeder extends Seeder
{
    /**
     * Migrasi 8 negara yang sebelumnya hardcoded di home.blade.php.
     *
     * PENTING: Gambar lama ada di public/images/countries/*.jpg (asset
     * biasa, bukan lewat Storage). Supaya konsisten dengan upload baru
     * dari admin (yang akan tersimpan lewat Storage disk 'public'),
     * seeder ini COPY FISIK file lama ke storage/app/public/countries/
     * lalu simpan path barunya ke database.
     *
     * Kalau file sumber tidak ditemukan (misal struktur folder Anda
     * beda), seeder akan skip copy untuk item itu dan kasih warning
     * di console — record tetap dibuat, tapi image_path perlu
     * diperbaiki manual lewat halaman edit admin nanti.
     */
    public function run(): void
    {
        $destinations = [
            [
                'flag_emoji' => '🇯🇵', 'name' => 'Jepang',
                'scholarship_name' => 'MEXT Scholarship',
                'source_file' => 'japan.jpg', 'display_order' => 1,
            ],
            [
                'flag_emoji' => '🇬🇧', 'name' => 'Inggris',
                'scholarship_name' => 'Chevening Scholarship',
                'source_file' => 'uk.jpg', 'display_order' => 2,
            ],
            [
                'flag_emoji' => '🇦🇺', 'name' => 'Australia',
                'scholarship_name' => 'Australia Awards (AAS)',
                'source_file' => 'australia.jpg', 'display_order' => 3,
            ],
            [
                'flag_emoji' => '🇺🇸', 'name' => 'Amerika Serikat',
                'scholarship_name' => 'Fulbright Scholarship',
                'source_file' => 'usa.jpg', 'display_order' => 4,
            ],
            [
                'flag_emoji' => '🇩🇪', 'name' => 'Jerman',
                'scholarship_name' => 'DAAD Scholarship',
                'source_file' => 'germany.jpg', 'display_order' => 5,
            ],
            [
                'flag_emoji' => '🇰🇷', 'name' => 'Korea Selatan',
                'scholarship_name' => 'Global Korea Scholarship',
                'source_file' => 'korea.jpg', 'display_order' => 6,
            ],
            [
                'flag_emoji' => '🇳🇱', 'name' => 'Belanda',
                'scholarship_name' => 'Erasmus+ Programme',
                'source_file' => 'netherlands.jpg', 'display_order' => 7,
            ],
            [
                'flag_emoji' => '🇮🇩', 'name' => 'Indonesia',
                'scholarship_name' => 'LPDP Scholarship',
                'source_file' => 'indonesia.jpg', 'display_order' => 8,
            ],
        ];

        foreach ($destinations as $item) {
            $sourcePath = public_path('images/countries/' . $item['source_file']);
            $storageRelativePath = 'countries/' . $item['source_file'];

            if (file_exists($sourcePath)) {
                Storage::disk('public')->put(
                    $storageRelativePath,
                    file_get_contents($sourcePath)
                );
            } else {
                $this->command?->warn(
                    "File sumber tidak ditemukan: {$sourcePath}. " .
                    "Record '{$item['name']}' dibuat, tapi gambar perlu diupload manual lewat admin."
                );
            }

            StudyDestination::updateOrCreate(
                ['name' => $item['name']],
                [
                    'flag_emoji' => $item['flag_emoji'],
                    'scholarship_name' => $item['scholarship_name'],
                    'image_path' => $storageRelativePath,
                    'display_order' => $item['display_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
