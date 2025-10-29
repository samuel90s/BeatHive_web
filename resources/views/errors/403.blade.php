@extends('layouts.master')

@section('title', '403 – Forbidden')
@section('heading', 'Forbidden')

@section('content')
<div class="page-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
          <div class="card-body py-5">
            <div class="text-center">

              <img class="img-fluid mb-4"
                   src="{{ asset('assets/compiled/svg/error-403.svg') }}"
                   alt="Forbidden"
                   style="max-width: 420px;">

              <h1 class="fw-bold mb-2">Akses ditolak</h1>
              <p class="text-muted mb-4">
                Kamu tidak berhak mengakses halaman ini. Silakan kembali atau login dengan akun yang memiliki izin.
              </p>

              <div class="d-flex gap-2 justify-content-center">
                <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : url('/') }}"
                   class="btn btn-dark">
                  <i class="bi bi-house me-1"></i> Kembali ke Beranda
                </a>
                @guest
                  <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                  </a>
                @endguest
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection
