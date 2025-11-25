<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil utama.
     */
    public function index(Request $request): View
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * (Opsional) Halaman edit terpisah jika masih digunakan.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update profil user (nama, email, avatar, bio, social_links, dsb).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'display_name' => ['nullable', 'string', 'max:100'],
            'username'      => ['nullable', 'alpha_dash', 'min:3', 'max:32', 'unique:users,username,' . $user->id],
            'email'         => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone'         => ['nullable', 'string', 'max:20'],
            'bio'           => ['nullable', 'string'],
            'social_links'  => ['nullable', 'array'],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
            
        ]);

        DB::beginTransaction();
        try {
            // 1. Email berubah -> reset verifikasi
            if ($validated['email'] !== $user->email) {
                $user->email_verified_at = null;
            }

            // 2. Social links (disimpan sebagai JSON/array di kolom user->social_links)
            if (array_key_exists('social_links', $validated)) {
                $user->social_links = $validated['social_links'];
                unset($validated['social_links']);
            }

            // 3. Avatar: hapus / ganti file
            if ($request->boolean('remove_avatar')) {
                // hapus file lama kalau ada
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = null;
            }

            if ($request->hasFile('avatar')) {
                // hapus file lama dulu
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $path = $request->file('avatar')->store('avatars', 'public'); // avatars/xxx.png
                $user->avatar = $path;
            }

            // avatar & remove_avatar jangan ikut fill()
            unset($validated['avatar'], $validated['remove_avatar']);

            // 4. Isi field lain dari $validated
            $user->fill($validated);
            $user->save();

            DB::commit();
            return Redirect::route('profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update password user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();
            return Redirect::route('profile.index')->with('success', 'Password updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Hapus akun secara permanen.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        DB::beginTransaction();
        try {
            Auth::logout();
            $user->delete();
            DB::commit();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('status', 'account-deleted');
        } catch (\Throwable $e) {
            DB::rollBack();
            Auth::login($user);
            throw $e;
        }
    }
}
