<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PricingController extends Controller
{
    public function __construct()
    {
        // Setup sederhana seperti di video YANG SUDAH WORK
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Display pricing page
     */
    public function index()
    {
        $pricingConfig = config('pricing.plans');
        
        return view('pricing.index', [
            'plans' => $pricingConfig,
            'priceBasic' => $pricingConfig['basic']['price'],
            'priceCreator' => $pricingConfig['entrepreneur']['price'],
            'pricePro' => $pricingConfig['professional']['price'],
        ]);
    }

    /**
     * Checkout process
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'package' => 'required|in:basic,entrepreneur,professional'
        ]);

        $user = auth()->user();
        $package = $request->package;

        Log::info('Checkout initiated for package: ' . $package);

        // Check if user already has this package
        if ($user->hasPackage($package) && $user->hasActivePackage()) {
            return redirect()->route('pricing.index')
                ->with('info', 'Anda sudah berlangganan paket ' . ucfirst($package));
        }

        // Get price from config - SATU SUMBER KEBENARAN
        $pricingConfig = config('pricing.plans');
        $amount = $pricingConfig[$package]['price'];

        // Handle free package (basic)
        if ($package === 'basic') {
            return $this->activateFreePackage($user, $package);
        }

        try {
            // Buat order ID
            $orderId = 'BH-' . time() . '-' . rand(1000, 9999);

            // Parameter transaction
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $amount,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id' => $package,
                        'price' => $amount,
                        'quantity' => 1,
                        'name' => $pricingConfig[$package]['display_name'] . ' Package - BeatHive',
                    ]
                ]
            ];

            Log::info('Calling Snap API with params:', $params);

            // Get Snap Token
            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap token received successfully');

            // Simpan order ke database
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'package' => $package,
                'amount' => $amount,
                'status' => 'pending',
                'snap_token' => $snapToken,
            ]);

            return view('payment.checkout', [
                'snapToken' => $snapToken,
                'order' => $order
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return redirect()->route('pricing.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ... method activateFreePackage tetap sama


    /**
     * Get package price
     */
    private function getPackagePrice($package)
    {
        $prices = [
            'basic' => 0,
            'entrepreneur' => 159000,
            'professional' => 299000,
        ];

        return $prices[$package];
    }

    /**
     * Activate free package
     */
    private function activateFreePackage($user, $package)
    {
        try {
            $user->update([
                'package' => $package,
                'package_activated_at' => now(),
                'package_expires_at' => now()->addMonth()
            ]);
            
            Log::info("Basic package activated for user: {$user->id}");
            
            return redirect()->route('dashboard')
                ->with('success', 'Paket Basic berhasil diaktifkan! Selamat menikmati musik gratis.');

        } catch (\Exception $e) {
            Log::error("Error activating basic package: " . $e->getMessage());
            
            return redirect()->route('pricing.index')
                ->with('error', 'Terjadi kesalahan saat mengaktifkan paket. Silakan coba lagi.');
        }
    }

    /**
     * Payment success page - METHOD YANG DIPANGGIL OLEH ROUTE
     */
    public function paymentSuccess(Request $request)
    {
        $orderId = $request->order_id;
        $order = null;
        
        // Update order status jika perlu
        if ($orderId) {
            $order = Order::where('order_id', $orderId)->first();
            if ($order) {
                $order->update(['status' => 'success']);
                $order->user->update([
                    'package' => $order->package,
                    'package_activated_at' => now(),
                    'package_expires_at' => now()->addMonth()
                ]);
            }
        }

        return view('payment.success', compact('order'));
    }

    /**
     * Payment pending page - METHOD YANG DIPANGGIL OLEH ROUTE
     */
    public function paymentPending(Request $request)
    {
        $orderId = $request->order_id;
        $order = null;
        
        if ($orderId) {
            $order = Order::where('order_id', $orderId)->first();
        }

        return view('payment.pending', compact('order'));
    }

    /**
     * Payment error page - METHOD YANG DIPANGGIL OLEH ROUTE
     */
    public function paymentError(Request $request)
    {
        $orderId = $request->order_id;
        $order = null;
        
        if ($orderId) {
            $order = Order::where('order_id', $orderId)->first();
            if ($order) {
                $order->update(['status' => 'failed']);
            }
        }

        return view('payment.error', compact('order'));
    }

    /**
     * Payment finish handler (jika ada route ini)
     */
    public function paymentFinish(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::where('order_id', $orderId)->first();

        if ($order) {
            return $this->paymentSuccess($request);
        }

        return redirect()->route('pricing.index');
    }

    /**
     * Payment notification handler (webhook)
     */
    public function paymentNotification(Request $request)
    {
        // Untuk sementara, redirect ke success
        $orderId = $request->order_id;
        if ($orderId) {
            return $this->paymentSuccess($request);
        }

        return response()->json(['status' => 'ok']);
    }
}