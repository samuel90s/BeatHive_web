<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundCategory extends Model
{
    use HasFactory;

    protected $table = 'sound_categories';
    protected $fillable = ['name','slug','bg_color','icon_path'];

    // relasi
    public function soundEffects()
    {
        return $this->hasMany(SoundEffect::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(SoundSubcategory::class, 'category_id');
    }
}
