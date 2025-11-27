<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Package types
    const PACKAGE_BASIC = 'basic';
    const PACKAGE_ENTREPRENEUR = 'entrepreneur';
    const PACKAGE_PROFESSIONAL = 'professional';

    // Status types
    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'user_id',
        'order_id', 
        'package',
        'amount',
        'status',
        'snap_token',
        'midtrans_response'
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method untuk format amount
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Get package price
    public static function getPackagePrice($package)
    {
        $prices = [
            self::PACKAGE_BASIC => 0,
            self::PACKAGE_ENTREPRENEUR => 159000,
            self::PACKAGE_PROFESSIONAL => 299000
        ];

        return $prices[$package] ?? 0;
    }

    // Mark as success
    public function markAsSuccess()
    {
        $this->update(['status' => self::STATUS_SUCCESS]);
        
        // Aktifkan paket user
        $this->user->update([
            'package' => $this->package,
            'package_activated_at' => now(),
            'package_expires_at' => now()->addMonth() // 1 bulan
        ]);
    }

    // Mark as failed
    public function markAsFailed()
    {
        $this->update(['status' => self::STATUS_FAILED]);
    }
}