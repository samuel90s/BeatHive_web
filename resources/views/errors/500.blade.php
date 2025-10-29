@extends('layouts.master')

@section('title', '500 – System Error')
@section('heading', 'System Error')

@section('content')
<div class="page-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
          <div class="card-body py-5">
            <div class="text-center">

              <img class="img-fluid mb-4"
                   src="{{ asset('assets/compiled/svg/error-500.svg') }}"
                   alt="System Error"
                   style="max-width: 420px;">

              <h1 class="fw-bold mb-2">Terjadi kesalahan sistem</h1>
              <p class="text-muted mb-4">
                Website sedang mengalami gangguan. Coba beberapa saat lagi atau hubungi developer.
              </p>

              <div class="d-flex gap-2 justify-content-center">
                <a href="{{ url()->previous() }}"
                   class="btn btn-outline-secondary">
                  <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : url('/') }}"
                   class="btn btn-dark">
                  <i class="bi bi-house me-1"></i> Ke Beranda
                </a>
              </div>

            </div>
          </div>
        </div>

        {{-- (opsional) detail debug saat APP_DEBUG=true bisa ditampilkan terpisah --}}

      </div>
    </div>
  </div>
</div>
@endsection
