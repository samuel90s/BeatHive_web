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

    // ---- Package constants
    public const PACKAGE_BASIC = 'basic';
    public const PACKAGE_ENTREPRENEUR = 'entrepreneur';
    public const PACKAGE_PROFESSIONAL = 'professional';

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
        // Package fields
        'package',
        'package_activated_at',
        'package_expires_at',
        'package_features',
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
            'social_links'      => 'array',
            // Package casts
            'package_activated_at' => 'datetime',
            'package_expires_at' => 'datetime',
            'package_features' => 'array',
        ];
    }

    // ---- Role helpers
    public function isAdmin(): bool  { return $this->role === self::ROLE_ADMIN; }
    public function isAuthor(): bool { return $this->role === self::ROLE_AUTHOR; }
    public function isUser(): bool   { return $this->role === self::ROLE_USER; }

    // ---- Package related methods

    /**
     * Check if user has specific package
     */
    public function hasPackage($package): bool
    {
        return $this->package === $package;
    }

    /**
     * Check if user has active package
     */
    public function hasActivePackage(): bool
    {
        return $this->package_expires_at && $this->package_expires_at->isFuture();
    }

    /**
     * Upgrade user package
     */
    public function upgradePackage($package)
    {
        $this->update([
            'package' => $package,
            'package_activated_at' => now(),
            'package_expires_at' => now()->addMonth() // 1 bulan
        ]);
    }

    /**
     * Get current package name
     */
    public function getPackageNameAttribute(): string
    {
        return ucfirst($this->package);
    }

    /**
     * Check if user can access premium features
     */
    public function canAccessPremium(): bool
    {
        return $this->package !== self::PACKAGE_BASIC && $this->hasActivePackage();
    }

    /**
     * Get package badge color
     */
    public function getPackageBadgeAttribute(): string
    {
        return match($this->package) {
            self::PACKAGE_BASIC => 'secondary',
            self::PACKAGE_ENTREPRENEUR => 'primary',
            self::PACKAGE_PROFESSIONAL => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get package features based on current package
     */
    public function getPackageFeaturesAttribute(): array
    {
        $features = [
            self::PACKAGE_BASIC => [
                'Limited downloads per day',
                'Core categories access',
                'Personal, non-commercial license',
                'Community support'
            ],
            self::PACKAGE_ENTREPRENEUR => [
                'All categories & tags',
                'Unlimited previews & collections',
                'Commercial license (individual)',
                'Priority support',
                'Early access to new packs'
            ],
            self::PACKAGE_PROFESSIONAL => [
                'Everything in Entrepreneur',
                '3 team seats included',
                'Client project licensing',
                'Top-priority support & SLA',
                'Company billing & invoices'
            ]
        ];

        return $features[$this->package] ?? [];
    }

    // ---- Relationships

    /**
     * Relationship with orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get latest active order
     */
    public function latestActiveOrder()
    {
        return $this->hasOne(Order::class)
            ->where('status', Order::STATUS_SUCCESS)
            ->latest();
    }

    // ---- Scopes

    /**
     * Scope for users with specific package
     */
    public function scopeWithPackage($query, $package)
    {
        return $query->where('package', $package);
    }

    /**
     * Scope for users with active subscription
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->whereNotNull('package_expires_at')
                    ->where('package_expires_at', '>', now());
    }

    public function scopeActive($q)  { return $q->where('status', 1); }
    public function scopeAdmins($q)  { return $q->where('role', self::ROLE_ADMIN); }
    public function scopeAuthors($q) { return $q->where('role', self::ROLE_AUTHOR); }

    // ---- Accessors
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return Storage::disk('public')->url($this->avatar);
        }
        return asset('assets/compiled/jpg/1.jpg');
    }

    /**
     * Get formatted package expiry date
     */
    public function getPackageExpiryFormattedAttribute(): ?string
    {
        return $this->package_expires_at?->format('d M Y');
    }

    /**
     * Check if package is expiring soon (within 7 days)
     */
    public function getIsPackageExpiringSoonAttribute(): bool
    {
        return $this->package_expires_at && 
               $this->package_expires_at->between(now(), now()->addDays(7));
    }
}