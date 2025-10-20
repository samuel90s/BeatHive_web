<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    // ===== Role mapping (int) =====
    private const ROLE_ADMIN  = 1;
    private const ROLE_AUTHOR = 2;
    private const ROLE_USER   = 3; // default

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

    public function showReset(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // --------- ACTIONS ---------
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();
        $ok = false;

        if ($user) {
            $stored = (string) $user->password;
            $plain  = (string) $data['password'];

            // 1) Hash modern (bcrypt/argon)
            if (str_starts_with($stored, '$')) {
                $ok = Hash::check($plain, $stored);

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

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate(); ; // harden session fixation

        // Catat aktivitas login (kolom opsional)
        $fills = [];
        if (schema_has_column('users', 'last_login_at')) {
            $fills['last_login_at'] = now();
        }
        if (schema_has_column('users', 'last_login_ip')) {
            $fills['last_login_ip'] = $request->ip();
        }
        if ($fills) {
            $user->forceFill($fills)->save();
        }

        $role = (int) ($user->role ?? self::ROLE_USER);

        // Arahkan sesuai role (sementara semua ke home)
        switch ($role) {
            case self::ROLE_ADMIN:
            case self::ROLE_AUTHOR:
            case self::ROLE_USER:
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
            'password'              => ['required', 'confirmed', PasswordRule::min(6)],
            'password_confirmation' => ['required'],
        ]);

        $user = new User();
        $user->name         = $data['name'];
        $user->username     = $data['username'];
        $user->email        = $data['email'];
        $user->password     = Hash::make($data['password']);
        $user->role         = self::ROLE_USER;  // default: user biasa
        // isi display_name jika kolomnya ada
        if (schema_has_column('users', 'display_name')) {
            $user->display_name = $data['name'];
        }
        // status aktif jika kolom ada & belum default
        if (schema_has_column('users', 'status') && $user->status === null) {
            $user->status = 1;
        }
        $user->save();

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate(); 

        return redirect()->intended(route('home'))->with('success', 'Registrasi berhasil.');
    }

    // Kirim link reset password via email
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(6)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
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
 * Helper kecil untuk cek kolom ada
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
