@extends('layouts.master')

@section('title', 'Pricing – BeatHive')
@section('heading', 'Pricing')
@section('subheading', 'Simple plans for creators and teams.')

@section('content')
<div class="page-content">
  <div class="card-body">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Compact Pricing Cards -->
    <section class="pricing-section">
      <div class="container-fluid">
        <div class="row justify-content-center g-3">
          @foreach($plans as $key => $plan)
          <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="pricing-card {{ $key == 'entrepreneur' ? 'pricing-card-popular' : '' }} h-100">
              @if($key == 'entrepreneur')
              <div class="popular-badge">
                <i class="fas fa-crown me-1"></i>Most Popular
              </div>
              @endif
              
              <div class="pricing-header text-center">
                <div class="plan-icon mb-2">
                  @if($key == 'basic')
                  <i class="fas fa-star"></i>
                  @elseif($key == 'entrepreneur')
                  <i class="fas fa-rocket"></i>
                  @else
                  <i class="fas fa-gem"></i>
                  @endif
                </div>
                <h4 class="plan-name mb-1">{{ $plan['display_name'] }}</h4>
                <p class="plan-description small mb-2">{{ $plan['description'] ?? '' }}</p>
                
                <div class="price-wrapper mb-3">
                  @if($plan['price'] == 0)
                  <h2 class="price-free mb-0">FREE</h2>
                  @else
                  <h2 class="price mb-0">Rp {{ number_format($plan['price'], 0, ',', '.') }}</h2>
                  <small class="price-period">/month</small>
                  @endif
                </div>
              </div>

              <div class="pricing-features">
                <ul class="features-list">
                  @foreach(array_slice($plan['features'], 0, 6) as $feature) {{-- Limit to 6 features --}}
                  <li class="feature-item">
                    <i class="fas fa-check feature-icon"></i>
                    <span class="small">{{ $feature }}</span>
                  </li>
                  @endforeach
                  @if(count($plan['features']) > 6)
                  <li class="feature-item text-muted small">
                    <i class="fas fa-plus feature-icon"></i>
                    <span>+{{ count($plan['features']) - 6 }} more features</span>
                  </li>
                  @endif
                </ul>
              </div>

              <div class="pricing-footer mt-auto">
                <form action="{{ route('checkout') }}" method="POST">
                  @csrf
                  <input type="hidden" name="package" value="{{ $key }}">
                  <button type="submit" class="btn {{ $key == 'entrepreneur' ? 'btn-premium' : 'btn-primary' }} w-100">
                    @if($plan['price'] == 0)
                    Get Started Free
                    @else
                    Choose Plan
                    @endif
                  </button>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- Compact Additional Info -->
    <section class="additional-info mt-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="info-card">
              <h5 class="info-title text-center mb-3">All Plans Include</h5>
              <div class="row g-2 text-center">
                <div class="col-md-3 col-6">
                  <div class="feature-highlight">
                    <i class="fas fa-music"></i>
                    <span class="d-block small fw-bold mt-1">10K+ Tracks</span>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="feature-highlight">
                    <i class="fas fa-sync"></i>
                    <span class="d-block small fw-bold mt-1">Weekly Updates</span>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="feature-highlight">
                    <i class="fas fa-headphones"></i>
                    <span class="d-block small fw-bold mt-1">Preview First</span>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="feature-highlight">
                    <i class="fas fa-shield-alt"></i>
                    <span class="d-block small fw-bold mt-1">Secure</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<style>
.pricing-section {
  padding: 1rem 0;
}

.pricing-card {
  background: var(--card-bg);
  border: 1px solid var(--border-color);
  border-radius: 16px;
  padding: 1.5rem;
  transition: all 0.3s ease;
  position: relative;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.pricing-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px var(--shadow-color);
}

.pricing-card-popular {
  border-color: var(--primary-color);
  background: linear-gradient(135deg, var(--primary-gradient));
  color: white;
}

.pricing-card-popular:hover {
  transform: translateY(-5px) scale(1.02);
}

.popular-badge {
  position: absolute;
  top: -10px;
  left: 50%;
  transform: translateX(-50%);
  background: #ffd700;
  color: #000;
  padding: 6px 16px;
  border-radius: 16px;
  font-size: 0.75rem;
  font-weight: 600;
  box-shadow: 0 3px 10px rgba(255, 215, 0, 0.3);
}

.plan-icon {
  font-size: 2rem;
  color: var(--primary-color);
  margin-bottom: 0.5rem;
}

.pricing-card-popular .plan-icon {
  color: white;
}

.plan-name {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.plan-description {
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.pricing-card-popular .plan-description {
  color: rgba(255, 255, 255, 0.8);
}

.price-wrapper {
  margin: 1rem 0;
}

.price-free {
  font-size: 2rem;
  font-weight: 700;
  color: var(--success-color);
  margin: 0;
}

.price {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  color: var(--text-color);
}

.pricing-card-popular .price {
  color: white;
}

.price-period {
  color: var(--text-muted);
  font-size: 0.8rem;
}

.pricing-card-popular .price-period {
  color: rgba(255, 255, 255, 0.8);
}

.pricing-features {
  flex: 1;
  margin: 1rem 0;
}

.features-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 0.5rem;
  padding: 0.25rem 0;
}

.feature-icon {
  color: var(--success-color);
  margin-right: 0.5rem;
  margin-top: 0.1rem;
  flex-shrink: 0;
  font-size: 0.8rem;
}

.pricing-card-popular .feature-icon {
  color: #ffd700;
}

.feature-item span {
  line-height: 1.3;
  font-size: 0.875rem;
}

.pricing-footer {
  margin-top: auto;
}

.btn-premium {
  background: linear-gradient(135deg, #ffd700 0%, #ff6b6b 100%);
  border: none;
  color: #000;
  font-weight: 600;
  padding: 10px 20px;
  border-radius: 8px;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.btn-premium:hover {
  transform: translateY(-1px);
  box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
  color: #000;
}

.additional-info {
  padding: 1.5rem 0;
}

.info-card {
  background: var(--card-bg);
  padding: 1.5rem;
  border-radius: 12px;
  border: 1px solid var(--border-color);
}

.info-title {
  font-weight: 600;
  color: var(--text-color);
  margin-bottom: 1rem;
  font-size: 1.1rem;
}

.feature-highlight {
  padding: 0.5rem;
}

.feature-highlight i {
  font-size: 1.5rem;
  color: var(--primary-color);
  margin-bottom: 0.25rem;
}

.feature-highlight span {
  font-size: 0.8rem;
}

/* Dark Mode Variables */
:root {
  --card-bg: #ffffff;
  --border-color: #e9ecef;
  --shadow-color: rgba(0, 0, 0, 0.1);
  --text-color: #2c3e50;
  --text-muted: #6c757d;
  --primary-color: #667eea;
  --primary-gradient: #667eea 0%, #764ba2 100%;
  --success-color: #28a745;
}

[data-bs-theme="dark"] {
  --card-bg: #1e1e1e;
  --border-color: #2d3748;
  --shadow-color: rgba(0, 0, 0, 0.3);
  --text-color: #e2e8f0;
  --text-muted: #a0aec0;
  --primary-color: #7c3aed;
  --primary-gradient: #7c3aed 0%, #4c1d95 100%;
  --success-color: #48bb78;
}

/* Responsive Design */
@media (max-width: 768px) {
  .pricing-card {
    padding: 1.25rem;
  }
  
  .price, .price-free {
    font-size: 1.75rem;
  }
  
  .plan-icon {
    font-size: 1.75rem;
  }
}

@media (max-width: 576px) {
  .pricing-card-popular {
    transform: none;
  }
  
  .pricing-card-popular:hover {
    transform: translateY(-3px);
  }
}
</style>
@endsection