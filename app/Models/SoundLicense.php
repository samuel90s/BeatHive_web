<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoundLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'highlight',
        'features',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'highlight' => 'boolean',
        'features'  => 'array', // otomatis JSON ⇆ array
    ];

    public function soundEffects()
    {
        return $this->hasMany(SoundEffect::class, 'license_type_id');
    }
}
