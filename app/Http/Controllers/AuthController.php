<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // --------- PAGES ---------
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    // --------- ACTIONS ---------
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Ambil user by email
        $user = User::where('email', $data['email'])->first();

        $ok = false;
        if ($user) {
            $stored = (string) $user->password;
            $plain  = (string) $data['password'];

            // 1) Hash modern (bcrypt/argon) → pakai Hash::check
            if (str_starts_with($stored, '$')) {
                $ok = Hash::check($plain, $stored);

                // Optional: rehash jika perlu (mis. cost beda)
                if ($ok && Hash::needsRehash($stored)) {
                    $user->password = Hash::make($plain);
                    $user->save();
                }
            } else {
                // 2) Kompat lama: SHA-256 atau MD5 (sesuai sistem native)
                $ok = (hash('sha256', $plain) === strtolower($stored)) || (md5($plain) === strtolower($stored));

                // Jika cocok legacy → auto-upgrade ke bcrypt
                if ($ok) {
                    $user->password = Hash::make($plain);
                    $user->save();
                }
            }
        }

        if (!$ok) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah']);
        }

        // Login ke Laravel session
        Auth::login($user, false); // false = tanpa remember me (bisa ditambah nanti)

        // Redirect per role (opsional)
        $role = $user->role ?? 'user';
        if ($role === 'admin') {
            return redirect()->intended(route('home'));
        } elseif ($role === 'student') {
            // ganti dengan route student dashboard jika ada
            return redirect()->intended(route('home'));
        }
        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role     = $user->role ?? 'user'; // set default role jika kolom ada
        $user->save();

        Auth::login($user, false);

        return redirect()->intended(route('home'))->with('success', 'Registrasi berhasil.');
    }

    public function sendResetLink(Request $request)
    {
        // Placeholder: hanya menampilkan alert sukses.
        // Nanti bisa diganti ke Laravel Password Broker (email reset).
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        return back()->with('status', 'Link reset password telah dikirim (dummy).');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
