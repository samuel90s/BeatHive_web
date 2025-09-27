<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ===== Role mapping (int) =====
    private const ROLE_ADMIN   = 1;
    private const ROLE_ARTIST  = 2;
    private const ROLE_CUSTOMER= 3; // default
    private const ROLE_STUDENT = 4;

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

            // 1) Hash modern (bcrypt/argon)
            if (str_starts_with($stored, '$')) {
                $ok = Hash::check($plain, $stored);

                // Optional: rehash jika perlu
                if ($ok && Hash::needsRehash($stored)) {
                    $user->password = Hash::make($plain);
                    $user->save();
                }
            } else {
                // 2) Kompat lama: SHA-256 / MD5 → upgrade ke bcrypt jika cocok
                $ok = (hash('sha256', $plain) === strtolower($stored)) || (md5($plain) === strtolower($stored));
                if ($ok) {
                    $user->password = Hash::make($plain);
                    $user->save();
                }
            }
        }

        if (!$ok) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah!']);
        }

        // Login ke session Laravel
        Auth::login($user, false); // remember me: false (bisa ditambah nanti)

        // Optional: catat last_login_at jika kolomnya ada
        if (schema_has_column('users', 'last_login_at')) {
            $user->forceFill(['last_login_at' => now()])->save();
        }

        // Redirect per role (int)
        $role = (int) ($user->role ?? self::ROLE_CUSTOMER);
        switch ($role) {
            case self::ROLE_ADMIN:
                return redirect()->intended(route('home'));
            case self::ROLE_STUDENT:
                return redirect()->intended(route('home')); // ganti jika punya dashboard khusus
            case self::ROLE_ARTIST:
                return redirect()->intended(route('home')); // ganti jika punya dashboard artist
            case self::ROLE_CUSTOMER:
            default:
                return redirect()->intended(route('home'));
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:100'],
            'username'              => ['required', 'string', 'min:3', 'max:32', 'alpha_dash', 'unique:users,username'],
            'email'                 => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'              => ['required', 'confirmed', Password::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user = new User();
        $user->name     = $data['name'];
        $user->username = $data['username'];   // ⬅️ penting
        $user->email    = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role     = self::ROLE_CUSTOMER; // 3
        $user->save();

        Auth::login($user, false);

        return redirect()->intended(route('home'))->with('success', 'Registrasi berhasil.');
    }

    public function sendResetLink(Request $request)
    {
        // Placeholder (dummy). Nanti ganti dengan Password::sendResetLink
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

/**
 * Helper kecil untuk cek kolom ada (tanpa harus pakai Schema facade di setiap tempat).
 * Taruh di file ini biar simpel; bisa juga kamu pindahkan ke helper global.
 */
if (!function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
