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
        'is_published'     => 'boolean',
        'release_date'     => 'date',
        'price_idr'        => 'integer',
        'duration_seconds' => 'integer',
        'bpm'              => 'integer',
    ];

    // otomatis resolve via slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /** relasi: Track milik 1 Genre */
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    /** scope: hanya published */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** accessor harga format IDR */
    public function getPriceFormattedAttribute()
    {
        return 'IDR ' . number_format($this->price_idr, 0, ',', '.');
    }
}
