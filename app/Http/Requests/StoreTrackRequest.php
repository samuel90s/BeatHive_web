<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // atur sesuai kebutuhan auth
    }

    public function rules(): array
    {
        return [
            'title'            => 'required|string|max:255',
            'artist'           => 'required|string|max:255',
            'genre_id'         => 'required|exists:genres,id',
            'bpm'              => 'nullable|integer|min:40|max:220',
            'musical_key'      => 'nullable|string|max:8',
            'mood'             => 'nullable|string|max:100',
            'tags'             => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'price_idr'        => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:0',
            'release_date'     => 'nullable|date',
            'is_published'     => 'nullable|boolean',

            // nama input file sesuai form kamu:
            'cover_image'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'audio_preview' => 'nullable|mimetypes:audio/mpeg|max:10240',  // 10MB
            'audio_bundle'  => 'nullable|mimes:wav,zip|max:204800',        // 200MB
        ];
    }
}
