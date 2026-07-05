<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class StudyDestination extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'flag_emoji',
        'name',
        'scholarship_name',
        'image_path',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // ============================================
    // Query Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('id');
    }

    // ============================================
    // Accessor: URL gambar publik
    // ============================================

    /**
     * URL gambar yang bisa langsung dipakai di <img src="">.
     * image_path disimpan relatif terhadap disk 'public'
     * (contoh: 'countries/japan-a1b2c3.jpg'), Storage::url()
     * yang urus prefix '/storage/'-nya.
     */
    public function getImageUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->image_path);
    }
}
