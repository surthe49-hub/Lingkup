<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'page',
        'section_key',
        'content',
        'content_type',
    ];

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeForPage($query, string $page)
    {
        return $query->where('page', $page);
    }

    // ============================================
    // Helper: Ambil semua konten 1 halaman sebagai array key-value
    // ============================================

    /**
     * Ambil semua section_key => content untuk 1 halaman.
     * Dipakai di Controller publik (Landing/Home/About) supaya
     * blade tinggal akses $content['section_key'].
     *
     * Di-cache 1 jam supaya tidak query database di setiap page load
     * halaman publik yang traffic-nya tinggi. Cache di-clear otomatis
     * setiap kali admin update konten (lihat PageContentController).
     *
     * @return array<string, string>
     */
    public static function getForPage(string $page): array
    {
        return Cache::remember("page_content:{$page}", 3600, function () use ($page) {
            return static::forPage($page)->pluck('content', 'section_key')->toArray();
        });
    }

    /**
     * Clear cache untuk 1 halaman. Dipanggil setelah admin update.
     */
    public static function clearCache(string $page): void
    {
        Cache::forget("page_content:{$page}");
    }
}
