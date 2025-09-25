<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','artist','genre_id','bpm','musical_key','mood',
        'tags','description','price_idr','duration_seconds','release_date',
        'is_published','cover_path','preview_path','bundle_path',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'release_date' => 'date',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
