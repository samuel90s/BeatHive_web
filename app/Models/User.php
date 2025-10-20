<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ---- Role constants
    public const ROLE_ADMIN  = 1;
    public const ROLE_AUTHOR = 2; // author
    public const ROLE_USER   = 3; // default

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'display_name',
        'bio',
        'social_links',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * Hidden attributes for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'role'              => 'integer',
            'status'            => 'integer',
            'social_links'      => 'array', // simpan/ambil sebagai array (kolom boleh longtext/json)
        ];
    }

    // ---- Role helpers
    public function isAdmin(): bool  { return $this->role === self::ROLE_ADMIN; }
    public function isAuthor(): bool { return $this->role === self::ROLE_AUTHOR; }
    public function isUser(): bool   { return $this->role === self::ROLE_USER; }

    // ---- Scopes
    public function scopeActive($q)  { return $q->where('status', 1); }
    public function scopeAdmins($q)  { return $q->where('role', self::ROLE_ADMIN); }
    public function scopeAuthors($q) { return $q->where('role', self::ROLE_AUTHOR); }

    // ---- Accessors
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // sesuaikan disk jika kamu simpan full-url di DB, bisa langsung return $this->avatar;
            return Storage::disk('public')->url($this->avatar);
        }
        // fallback default
        return asset('assets/compiled/jpg/1.jpg');
    }
}
