<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundLicense extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','price'];

    public function soundEffects() {
        return $this->hasMany(SoundEffect::class, 'license_type_id');
    }
}
