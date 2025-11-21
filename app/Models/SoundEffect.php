<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SoundEffect extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','file_path','file_ext','mime_type','size_bytes',
        'duration_seconds','sample_rate','channels','bitrate_kbps','bit_depth',
        'loudness_lufs','peak_dbfs','preview_path','waveform_image','fingerprint',
        'category_id','license_type_id','creator_user_id','is_active','analysis_status'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_seconds' => 'decimal:3',
        'loudness_lufs' => 'decimal:2',
        'peak_dbfs' => 'decimal:2',
    ];

    // === Relationships ===
    // public function category() {
    //     return $this->belongsTo(SoundCategory::class);
    // }

    public function license() {
        return $this->belongsTo(SoundLicense::class, 'license_type_id');
    }

    public function author() {
        return $this->belongsTo(User::class, 'creator_user_id');
    }

    public function tags() {
        return $this->belongsToMany(SoundTag::class, 'sound_effect_tag');
    }

    // === Helpers ===
    public static function generateSlug($title)
    {
        return Str::slug($title) . '-' . substr(sha1($title . now()), 0, 6);
    }
    public function category()
    {
        return $this->belongsTo(SoundCategory::class, 'category_id');
    }

    public function subcategory()
    {
        // pastikan nama kolomnya benar: subcategory_id
        return $this->belongsTo(SoundSubcategory::class, 'subcategory_id');
    }
}
