<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful – BeatHive</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #5a6fd8;
            --success-color: #28a745;
            --success-dark: #218838;
            --text-color: #2c3e50;
            --text-muted: #6c757d;
            --border-color: #e9ecef;
            --bg-light: #f8f9fa;
        }

        [data-bs-theme="dark"] {
            --primary-color: #7c3aed;
            --primary-dark: #6d28d9;
            --success-color: #48bb78;
            --success-dark: #38a169;
            --text-color: #e2e8f0;
            --text-muted: #a0aec0;
            --border-color: #2d3748;
            --bg-light: #1a202c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .success-container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        [data-bs-theme="dark"] .success-container {
            background: #1e1e1e;
        }

        .success-header {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 0.6s ease-in-out;
        }

        .success-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .success-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .success-body {
            padding: 2rem;
        }

        .package-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .order-details {
            background: var(--bg-light);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--border-color);
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .detail-value {
            color: var(--text-color);
            font-weight: 600;
        }

        .detail-value.price {
            color: var(--success-color);
            font-weight: 700;
        }

        .status-badge {
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            background: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            gap: 0.5rem;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .action-btn.outline {
            background: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .action-btn.outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .action-btn.success {
            background: linear-gradient(135deg, var(--success-color) 0%, var(--success-dark) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .action-btn.success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .support-section {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .support-text {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .support-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        .support-link:hover {
            color: var(--primary-dark);
        }

        /* Animations */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-8px);
            }
            60% {
                transform: translateY(-4px);
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            body {
                padding: 15px;
            }
            
            .success-container {
                border-radius: 15px;
            }
            
            .success-header {
                padding: 2rem 1.5rem;
            }
            
            .success-body {
                padding: 1.5rem;
            }
            
            .success-title {
                font-size: 1.6rem;
            }
            
            .success-icon {
                font-size: 3.5rem;
            }
            
            .package-badge {
                padding: 0.6rem 1.2rem;
                font-size: 0.85rem;
            }
            
            .order-details {
                padding: 1.25rem;
            }
            
            .action-btn {
                padding: 0.875rem 1.25rem;
                font-size: 0.9rem;
            }
        }

        @media (max-height: 700px) {
            .success-header {
                padding: 2rem 1.5rem;
            }
            
            .success-body {
                padding: 1.5rem;
            }
            
            .package-badge {
                margin-bottom: 1rem;
            }
            
            .order-details {
                margin-bottom: 1rem;
            }
            
            .quick-actions {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <!-- Header -->
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-subtitle">{{ ucfirst($order->package) }} Plan Activated</p>
        </div>

        <!-- Body -->
        <div class="success-body">
            <!-- Package Badge -->
            <div class="text-center">
                <div class="package-badge">
                    <i class="fas fa-crown me-2"></i>PREMIUM ACCESS GRANTED
                </div>
            </div>

            <!-- Order Details -->
            <div class="order-details">
                <div class="detail-item">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value">{{ $order->order_id }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Package</span>
                    <span class="detail-value">{{ ucfirst($order->package) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Amount</span>
                    <span class="detail-value price">Rp {{ number_format($order->amount, 0, ',', '.') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value status-badge">Completed</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Next Billing</span>
                    <span class="detail-value">{{ now()->addMonth()->format('M d, Y') }}</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ url('/') }}" class="action-btn primary">
                    <i class="fas fa-home"></i>
                    Go to Homepage
                </a>
                <a href="{{ url('/dashboard') }}" class="action-btn outline">
                    <i class="fas fa-tachometer-alt"></i>
                    View Dashboard
                </a>
                <a href="{{ url('/browse-genres') }}" class="action-btn success">
                    <i class="fas fa-music"></i>
                    Browse Music
                </a>
            </div>

            <!-- Support -->
            <div class="support-section">
                <p class="support-text">Need help getting started?</p>
                <a href="mailto:support@beathive.com" class="support-link">
                    Contact Support <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>