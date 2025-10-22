<?php

namespace App\Http\Controllers;

use App\Models\SoundLicense;
use Illuminate\Http\Request;

class SoundLicenseController extends Controller
{
    public function index()
    {
        $licenses = SoundLicense::latest()->paginate(10);
        return view('sound_licenses.index', compact('licenses'));
    }

    public function create()
    {
        return view('sound_licenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:sound_licenses,name',
            'price' => 'nullable|numeric|min:0',
        ]);

        SoundLicense::create($request->only('name', 'description', 'price'));

        return redirect()->route('sound_licenses.index')->with('success', 'License added successfully!');
    }

    public function edit(SoundLicense $sound_license)
    {
        return view('sound_licenses.edit', compact('sound_license'));
    }

    public function update(Request $request, SoundLicense $sound_license)
    {
        $request->validate([
            'name' => 'required|max:100|unique:sound_licenses,name,' . $sound_license->id,
            'price' => 'nullable|numeric|min:0',
        ]);

        $sound_license->update($request->only('name', 'description', 'price'));

        return redirect()->route('sound_licenses.index')->with('success', 'License updated!');
    }

    public function destroy(SoundLicense $sound_license)
    {
        $sound_license->delete();
        return redirect()->route('sound_licenses.index')->with('success', 'License deleted.');
    }
}
