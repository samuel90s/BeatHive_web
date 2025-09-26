<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description',
    ];

    // otomatis resolve via slug, bukan id
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /** relasi: 1 Genre punya banyak Track */
    public function tracks()
    {
        return $this->hasMany(Track::class);
    }
}
