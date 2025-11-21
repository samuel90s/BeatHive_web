@extends('layouts.master')

@section('title', 'Pricing – BeatHive')
@section('heading', 'Pricing')
@section('subheading', 'Simple plans for creators and teams.')

@section('content')
<div class="page-content">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @php
    use Illuminate\Support\Str;

    $fmtShort = function ($price) {
        if (!$price || $price == 0) return "FREE";
        $th = $price / 1000;
        $short = $th >= 100
            ? number_format($th, 0)
            : rtrim(rtrim(number_format($th, 1, '.', ''), '0'), '.');
        return "Rp {$short}k";
    };

    $fmtFull = function ($price) {
        if (!$price || $price == 0) return "Free";
        return "Rp " . number_format($price, 0, ',', '.');
    };
  @endphp

  <section class="section">
    <div class="row justify-content-center">
      <div class="col-12 col-xl-10">

        <div class="row g-4 justify-content-center">

          @forelse($licenses as $lic)
            @php
              $lines = preg_split("/\r\n|\n|\r/", trim($lic->description));
              $lines = array_filter($lines, fn($l) => trim($l) !== '');
            @endphp

            <div class="col-md-4 d-flex">

              <div class="card w-100 h-100 shadow-lg pricing-card d-flex flex-column">
                
                {{-- HEADER --}}
                <div class="card-header text-center py-3 {{ $loop->iteration == 2 ? 'bg-dark text-white' : '' }}">
                  
                  <h4 class="card-title mb-1">{{ $lic->name }}</h4>
                  <p class="mb-0 small {{ $loop->iteration == 2 ? 'text-white-50' : 'text-muted' }}">
                    {{ $fmtFull($lic->price) }}
                  </p>

                  {{-- ADMIN ACTION --}}
                  @can('admin-only')
                    <div class="position-absolute top-0 end-0 m-2">
                      <a href="{{ route('sound_licenses.edit', $lic->id) }}"
                         class="btn btn-warning btn-sm me-1">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('sound_licenses.destroy', $lic->id) }}" 
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this license?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  @endcan

                </div>

                {{-- BODY --}}
                <div class="card-body text-center py-3 d-flex flex-column">

                  <h1 class="price mb-3 {{ $loop->iteration == 2 ? 'text-white' : '' }}">
                    {{ $fmtShort($lic->price) }}
                  </h1>

                  @if(!empty($lines))
                    <ul class="text-start small mb-0 {{ $loop->iteration == 2 ? 'text-white' : '' }}">
                      @foreach($lines as $line)
                        <li>
                          <i class="bi bi-check-circle{{ $loop->iteration == 2 ? '-fill' : '' }}"></i>
                          {{ $line }}
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <p class="text-muted small">
                      {{ Str::limit($lic->description ?? 'No description', 100) }}
                    </p>
                  @endif

                </div>

                {{-- FOOTER --}}
                <div class="card-footer bg-transparent border-0 mt-auto py-3">
                  <button 
                    class="btn {{ $loop->iteration == 2 ? 'btn-outline-light' : 'btn-primary' }} w-100 btn-sm">
                    Choose Plan
                  </button>
                </div>

              </div>
            </div>

          @empty
            <div class="text-center text-muted py-5">
              No licenses found.
            </div>
          @endforelse

        </div>

        {{-- Pagination --}}
        <div class="mt-4">
          {{ $licenses->links() }}
        </div>

      </div>
    </div>
  </section>

</div>
@endsection

@push('styles')
<style>
  .pricing-card {
    border-radius: 1rem;
  }

  .pricing-card .price {
    font-size: 2.4rem;
    font-weight: 700;
  }

  .pricing-card ul li i {
    color: #28a745;
    margin-right: 4px;
  }

  .card-highlighted {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,.25) !important;
  }
</style>
@endpush
