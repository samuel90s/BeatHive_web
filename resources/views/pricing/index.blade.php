@extends('layouts.master')

@section('title', 'Pricing – BeatHive')
@section('heading', 'Pricing')
@section('subheading', 'Simple plans for creators and teams.')

@section('content')
<div class="page-content">
  <div class="card-body">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
      $fmt = function ($n) {
          if ((int)$n === 0) {
              return 'FREE';
          }
          // 1 desimal kalau perlu (mis. 154000 => 154k, 159000 => 159k, 155500 => 155.5k)
          $thousands = $n / 1000;
          $short = $thousands >= 100
              ? number_format($thousands, 0)               // 100k, 250k, ...
              : rtrim(rtrim(number_format($thousands, 1, '.', ''), '0'), '.'); // 59k, 119k, 12.5k
          return $short . 'k';
      };
    @endphp


    <section class="section">
      <div class="row">
        <div class="col-12 col-md-10 offset-md-1">
          <div class="pricing">
            {{-- tarik sedikit ke atas agar center card tampak “menimpa” --}}
            <div class="row align-items-stretch justify-content-center">

              {{-- BASIC --}}
              {{-- BASIC --}}
              <div class="col-md-4 px-0 d-flex">
                <div class="card w-100 h-100 d-flex flex-column shadow">
                  <div class="card-header text-center py-3">
                    <h4 class="card-title mb-1">Basic</h4>
                    <p class="text-center mb-0">Standard features to get started.</p>
                  </div>
                  <div class="card-body text-center py-3">
                    <h1 class="price mb-3">{{ $fmt($priceBasic) }}</h1>
                    <ul class="text-start mb-0">
                      <li><i class="bi bi-check-circle"></i> Limited downloads per day</li>
                      <li><i class="bi bi-check-circle"></i> Core categories access</li>
                      <li><i class="bi bi-check-circle"></i> Personal, non-commercial license</li>
                      <li><i class="bi bi-check-circle"></i> Community support</li>
                      <li><i class="bi bi-check-circle"></i> Cancel anytime</li>
                      <li><i class="bi bi-check-circle"></i> No credit card required</li>
                    </ul>
                  </div>
                  <div class="card-footer bg-transparent border-0 py-3 mt-auto">
                    <button class="btn btn-primary w-100 btn-sm">Get Started</button>
                  </div>
                </div>
              </div>

              {{-- ENTREPRENEUR (center, elevated) --}}
              <div class="col-md-4 px-0 d-flex">
                <div class="card card-highlighted w-100 h-100 d-flex flex-column shadow-lg position-relative z-3 mt-n4" style="transform: translateY(-6px);">
                  <div class="card-header text-center py-3">
                    <h4 class="card-title mb-1">Entrepreneur</h4>
                    <p class="text-center mb-0">Best for YouTubers & freelancers.</p>
                  </div>
                  <div class="card-body text-center py-3">
                    <h1 class="price text-white mb-3">{{ $fmt($priceCreator) }}</h1>
                    <ul class="text-start mb-0">
                      <li><i class="bi bi-check-circle"></i> All categories & tags</li>
                      <li><i class="bi bi-check-circle"></i> Unlimited previews & collections</li>
                      <li><i class="bi bi-check-circle"></i> Commercial license (individual)</li>
                      <li><i class="bi bi-check-circle"></i> Priority support</li>
                      <li><i class="bi bi-check-circle"></i> Early access to new packs</li>
                      <li><i class="bi bi-check-circle"></i> Cancel anytime</li>
                      <li><i class="bi bi-check-circle"></i> Monthly billing</li>
                      <li><i class="bi bi-check-circle"></i> Taxes included where applicable</li>
                      <li><i class="bi bi-check-circle"></i> Fair use policy</li>
                    </ul>
                  </div>
                  <div class="card-footer bg-transparent border-0 py-3 mt-auto">
                    <button class="btn btn-outline-white w-100 btn-sm">Choose Plan</button>
                  </div>
                </div>
              </div>

              {{-- PROFESSIONAL --}}
              <div class="col-md-4 px-0 d-flex">
                <div class="card w-100 h-100 d-flex flex-column shadow">
                  <div class="card-header text-center py-3">
                    <h4 class="card-title mb-1">Professional</h4>
                    <p class="text-center mb-0">Advanced features for studios & teams.</p>
                  </div>
                  <div class="card-body text-center py-3">
                    <h1 class="price mb-3">{{ $fmt($pricePro) }}</h1>
                    <ul class="text-start mb-0">
                      <li><i class="bi bi-check-circle"></i> Everything in Entrepreneur</li>
                      <li><i class="bi bi-check-circle"></i> 3 team seats included</li>
                      <li><i class="bi bi-check-circle"></i> Client project licensing</li>
                      <li><i class="bi bi-check-circle"></i> Top-priority support & SLA</li>
                      <li><i class="bi bi-check-circle"></i> Company billing & invoices</li>
                      <li><i class="bi bi-check-circle"></i> Cancel anytime</li>
                    </ul>
                  </div>
                  <div class="card-footer bg-transparent border-0 py-3 mt-auto">
                    <button class="btn btn-primary w-100 btn-sm">Choose Plan</button>
                  </div>
                </div>
              </div>


            </div>{{-- /row --}}
          </div>{{-- /pricing --}}
        </div>
      </div>
    </section>
<div class="mb-5"></div>
  </div>
</div>
@endsection