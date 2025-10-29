@extends('layouts.master')

@section('title', '404 – Not Found')
@section('heading', 'Not Found')

@section('content')
<div class="page-content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card border-0 shadow-sm">
          <div class="card-body py-5">
            <div class="text-center">

              {{-- Ilustrasi --}}
              <img class="img-fluid mb-4" 
                   src="{{ asset('assets/compiled/svg/error-404.svg') }}" 
                   alt="Not Found" 
                   style="max-width: 420px;">

              {{-- Judul & deskripsi --}}
              <h1 class="fw-bold mb-2">Halaman tidak ditemukan</h1>
              <p class="text-muted mb-4">
                Maaf, halaman yang kamu cari tidak tersedia atau sudah dipindahkan.
              </p>

              {{-- Aksi --}}
              <div class="d-flex gap-2 justify-content-center">
                <a href="{{ Route::has('sound_effects.index') ? route('sound_effects.index') : url('/') }}" 
                   class="btn btn-dark">
                  <i class="bi bi-house me-1"></i> Kembali ke Beranda
                </a>

                {{-- (Opsional) tombol laporan / bantuan --}}
                {{-- <a href="{{ route('help.center') }}" class="btn btn-outline-secondary">
                  <i class="bi bi-life-preserver me-1"></i> Pusat Bantuan
                </a> --}}
              </div>

              {{-- (Opsional) quick search --}}
              {{-- 
              <form action="{{ url()->previous() }}" class="mt-4" onsubmit="return false;">
                <div class="input-group" style="max-width:520px;margin:0 auto;">
                  <span class="input-group-text bg-body"><i class="bi bi-search"></i></span>
                  <input type="text" class="form-control" placeholder="Cari kategori, tag, atau judul…">
                  <button class="btn btn-outline-secondary" disabled>Search</button>
                </div>
                <div class="form-text mt-1 text-muted">
                  Gunakan menu navigasi untuk mencari halaman yang kamu butuhkan.
                </div>
              </form>
              --}}

            </div>
          </div>
        </div>

        {{-- (Opsional) tautan cepat --}}
        {{-- 
        <div class="text-center mt-3">
          <a class="text-decoration-none" href="{{ route('sound_effects.list') }}">
            Lihat daftar Sound Effects
          </a>
        </div>
        --}}

      </div>
    </div>
  </div>
</div>
@endsection
