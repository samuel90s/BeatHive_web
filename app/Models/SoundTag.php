<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundTag extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];

    public function soundEffects() {
        return $this->belongsToMany(SoundEffect::class, 'sound_effect_tag');
    }
}
