<?php

namespace App\Http\Controllers;

use App\Models\SoundLicense;
use Illuminate\Http\Request;

class SoundLicenseController extends Controller
{
    /**
     * List all licenses (paginated).
     */
    public function index()
    {
        $licenses = SoundLicense::latest()->paginate(10);

        return view('sound_licenses.index', compact('licenses'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('sound_licenses.create');
    }

    /**
     * Store new license.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|max:100|unique:sound_licenses,name',
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            // kolom baru
            'highlight'   => 'sometimes|boolean',
            'features'    => 'nullable|string', // textarea, 1 fitur per baris
        ]);

        // convert textarea -> array fitur
        $features = null;
        if (!empty($data['features'])) {
            $lines    = preg_split("/\r\n|\n|\r/", $data['features']);
            $features = array_values(array_filter(array_map('trim', $lines)));
        }

        SoundLicense::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? 0,
            'highlight'   => $request->boolean('highlight'),
            'features'    => $features,
        ]);

        return redirect()
            ->route('sound_licenses.index')
            ->with('success', 'License added successfully!');
    }

    /**
     * Show edit form.
     */
    public function edit(SoundLicense $sound_license)
    {
        return view('sound_licenses.edit', compact('sound_license'));
    }

    /**
     * Update existing license.
     */
    public function update(Request $request, SoundLicense $sound_license)
    {
        $data = $request->validate([
            'name'        => 'required|max:100|unique:sound_licenses,name,' . $sound_license->id,
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'highlight'   => 'sometimes|boolean',
            'features'    => 'nullable|string',
        ]);

        $features = null;
        if (!empty($data['features'])) {
            $lines    = preg_split("/\r\n|\n|\r/", $data['features']);
            $features = array_values(array_filter(array_map('trim', $lines)));
        }

        $sound_license->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? 0,
            'highlight'   => $request->boolean('highlight'),
            'features'    => $features,
        ]);

        return redirect()
            ->route('sound_licenses.index')
            ->with('success', 'License updated!');
    }

    /**
     * Delete license.
     */
    public function destroy(SoundLicense $sound_license)
    {
        $sound_license->delete();

        return redirect()
            ->route('sound_licenses.index')
            ->with('success', 'License deleted.');
    }
}
