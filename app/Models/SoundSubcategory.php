<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundSubcategory extends Model
{
    use HasFactory;

    protected $table = 'sound_subcategories';

    // kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'category_id',
        'name',
        'slug',
    ];

    /**
     * Parent category (SoundCategory)
     */
    public function category()
    {
        return $this->belongsTo(SoundCategory::class, 'category_id');
    }

    /**
     * Relasi ke sound effects di subkategori ini.
     * Dipakai untuk withCount('soundEffects') di controller.
     */
    public function soundEffects()
    {
        return $this->hasMany(SoundEffect::class, 'subcategory_id');
    }
}
