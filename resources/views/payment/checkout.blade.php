<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - {{ ucfirst($order->package) }} Package | BeatHive</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a6fd8;
            --success-color: #28a745;
            --text-color: #2c3e50;
            --text-muted: #6c757d;
            --border-color: #e9ecef;
            --bg-light: #f8f9fa;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        [data-bs-theme="dark"] {
            --primary-color: #7c3aed;
            --primary-dark: #6d28d9;
            --text-color: #e2e8f0;
            --text-muted: #a0aec0;
            --border-color: #2d3748;
            --bg-light: #1a202c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            margin: 0;
        }

        .checkout-container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        [data-bs-theme="dark"] .checkout-container {
            background: #1e1e1e;
        }

        .checkout-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .checkout-body {
            padding: 2rem;
        }

        .order-summary {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .package-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .price-display {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 1rem 0;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 1rem 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 0;
            color: var(--text-color);
        }

        .feature-item i {
            color: var(--success-color);
            margin-right: 0.75rem;
            font-size: 0.9rem;
        }

        .user-info {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .info-value {
            color: var(--text-color);
            font-weight: 600;
        }

        .payment-action {
            margin-top: 2rem;
        }

        .btn-pay {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.25rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .btn-pay:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .terms-agreement {
            margin-top: 1.5rem;
            padding: 1rem;
            background: var(--bg-light);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .security-note {
            text-align: center;
            margin-top: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .security-note i {
            color: var(--success-color);
            margin-right: 0.5rem;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .checkout-container {
                margin: 20px auto;
                border-radius: 15px;
            }
            
            .checkout-header {
                padding: 1.5rem;
            }
            
            .checkout-body {
                padding: 1.5rem;
            }
            
            .price-display {
                font-size: 2rem;
            }
        }

        @media (max-width: 576px) {
            .checkout-header h2 {
                font-size: 1.5rem;
            }
            
            .checkout-body {
                padding: 1rem;
            }
            
            .btn-pay {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Header -->
        <div class="checkout-header">
            <h2 class="mb-3"><i class="fas fa-lock me-2"></i>Secure Checkout</h2>
            <p class="mb-0">Complete your purchase securely with Midtrans</p>
        </div>

        <!-- Body -->
        <div class="checkout-body">
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="package-badge">
                    <i class="fas fa-crown me-2"></i>{{ ucfirst($order->package) }} PLAN
                </div>
                
                <div class="price-display">
                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                    <small class="text-muted fs-6">/month</small>
                </div>

                <div class="features-list">
                    @php
                        $features = config("pricing.plans.{$order->package}.features", []);
                    @endphp
                    @foreach(array_slice($features, 0, 4) as $feature)
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ $feature }}</span>
                    </div>
                    @endforeach
                    @if(count($features) > 4)
                    <div class="feature-item text-muted">
                        <i class="fas fa-plus-circle"></i>
                        <span>+{{ count($features) - 4 }} more features</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Information -->
            <div class="user-info">
                <h5 class="mb-3"><i class="fas fa-user me-2"></i>Billing Information</h5>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ auth()->user()->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ auth()->user()->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    <span class="info-value">{{ $order->order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">Midtrans Secure Payment</span>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="terms-agreement">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="termsAgreement" required>
                    <label class="form-check-label" for="termsAgreement">
                        I agree to the <a href="#" class="text-primary">Terms of Service</a> and 
                        <a href="#" class="text-primary">Privacy Policy</a>. I understand that I'll get 
                        immediate access to BeatHive and can cancel anytime.
                    </label>
                </div>
            </div>

            <!-- Payment Action -->
            <div class="payment-action">
                <button id="pay-button" class="btn-pay" disabled>
                    <i class="fas fa-credit-card me-2"></i>
                    Pay Rp {{ number_format($order->amount, 0, ',', '.') }}
                </button>
                
                <div class="security-note">
                    <i class="fas fa-shield-alt"></i>
                    Your payment is secured and encrypted
                </div>
            </div>

            <!-- Back Link -->
            <div class="back-link">
                <a href="{{ route('pricing.index') }}">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Pricing
                </a>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const payButton = document.getElementById('pay-button');
            const termsCheckbox = document.getElementById('termsAgreement');

            // Enable/disable pay button based on terms agreement
            termsCheckbox.addEventListener('change', function() {
                payButton.disabled = !this.checked;
            });

            // Payment handler
            payButton.addEventListener('click', function() {
                if (!termsCheckbox.checked) {
                    alert('Please agree to the terms and conditions before proceeding.');
                    return;
                }

                // Disable button and show loading
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing Payment...';

                // Trigger Midtrans Snap
                snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result) {
                        console.log('Payment Success:', result);
                        window.location.href = '/payment/success?order_id=' + result.order_id;
                    },
                    onPending: function(result) {
                        console.log('Payment Pending:', result);
                        window.location.href = '/payment/pending?order_id=' + result.order_id;
                    },
                    onError: function(result) {
                        console.log('Payment Error:', result);
                        alert('Payment failed. Please try again.');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay Rp {{ number_format($order->amount, 0, ",", ".") }}';
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="fas fa-credit-card me-2"></i>Pay Rp {{ number_format($order->amount, 0, ",", ".") }}';
                    }
                });
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>