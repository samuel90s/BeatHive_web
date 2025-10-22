<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoundSubcategory extends Model
{
    protected $fillable = ['category_id', 'name', 'slug'];

    public function category()
    {
        return $this->belongsTo(SoundCategory::class, 'category_id');
    }

    public function soundEffects()
    {
        return $this->hasMany(SoundEffect::class, 'subcategory_id');
    }
}
