<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundTag extends Model
{
    use HasFactory;

    protected $table = 'sound_tags';

    protected $fillable = ['name','slug'];

    public function soundEffects()
    {
        // foreignPivotKey   = tag_id
        // relatedPivotKey   = sound_effect_id
        return $this->belongsToMany(SoundEffect::class, 'sound_effect_tag', 'tag_id', 'sound_effect_id');
    }
}
