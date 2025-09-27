{{-- resources/views/index.blade.php --}}
@extends('layouts.master')

@section('title', 'BeatHive – Dashboard')
@section('heading', 'Dashboard – Stock Music')
@section('subheading', 'Ringkasan performa dan aktivitas katalog.')

@push('head')
  <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon" />
  {{-- ApexCharts (pakai yang sudah ada di template-mu) --}}
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}" />
@endpush

@section('content')
<div class="page-content">
  <section class="row">
    <div class="col-12 col-lg-9">
      {{-- KPI Cards --}}
      <div class="row g-4">
        <div class="col-6 col-lg-4 col-md-6">
          <div class="card">
            <div class="card-body py-4 px-5">
              <div class="d-flex align-items-center">
                <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                  <i class="bi bi-soundwave fs-5"></i>
                </div>
                <div>
                  <h5 class="mb-0 fw-bold">{{ number_format($totalTracks) }}</h5>
                  <h6 class="mb-0 text-muted fs-6">Total Tracks</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-4 col-md-6">
          <div class="card">
            <div class="card-body py-4 px-5">
              <div class="d-flex align-items-center">
                <div class="bg-success text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                  <i class="bi bi-check2-circle fs-5"></i>
                </div>
                <div>
                  <h5 class="mb-0 fw-bold">{{ number_format($published) }}</h5>
                  <h6 class="mb-0 text-muted fs-6">Published</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-4 col-md-6">
          <div class="card">
            <div class="card-body py-4 px-5">
              <div class="d-flex align-items-center">
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                  <i class="bi bi-pencil-square fs-5"></i>
                </div>
                <div>
                  <h5 class="mb-0 fw-bold">{{ number_format($draft) }}</h5>
                  <h6 class="mb-0 text-muted fs-6">Draft</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Charts --}}
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header"><h4>Tracks Added (12 months)</h4></div>
            <div class="card-body">
              <div id="chart-tracks-added"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- Latest items (ubah "Latest Sales" → "Recent Tracks") --}}
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header"><h4>Recent Tracks</h4></div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover table-lg">
                  <thead>
                    <tr>
                      <th>Title</th>
                      <th>Artist</th>
                      <th>Genre</th>
                      <th>Status</th>
                      <th>Price</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentTracks as $t)
                      <tr>
                        <td class="col-4">
                          <a href="{{ route('tracks.show', $t) }}" class="fw-semibold">{{ $t->title }}</a>
                          <div class="text-muted small">{{ $t->slug }}</div>
                        </td>
                        <td class="col-3">{{ $t->artist }}</td>
                        <td class="col-2">{{ optional($t->genre)->name ?? '-' }}</td>
                        <td class="col-1">
                          @if($t->is_published)
                            <span class="badge bg-success">Published</span>
                          @else
                            <span class="badge bg-secondary">Draft</span>
                          @endif
                        </td>
                        <td class="col-2"><strong>
                          @if(!is_null($t->price_idr))
                            IDR {{ number_format($t->price_idr, 0, ',', '.') }}
                          @else
                            -
                          @endif
                        </strong></td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada track.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <div class="text-end">
                <a href="{{ route('tracks.index') }}" class="btn btn-outline-primary btn-sm">View all tracks</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- Right column --}}
    <div class="col-12 col-lg-3">
      {{-- Profile card (optional dynamic) --}}
      <div class="card">
        <div class="card-body py-4 px-5">
          <div class="d-flex align-items-center">
            <div class="avatar avatar-xl">
              <img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Avatar" />
            </div>
            <div class="ms-3 name">
              <h5 class="font-bold">{{ Auth::user()->name ?? 'BeatHive Admin' }}</h5>
              <h6 class="text-muted mb-0">@beathive</h6>
            </div>
          </div>
        </div>
      </div>

      {{-- Top Genres --}}
      <div class="card">
        <div class="card-header"><h4>Top Genres</h4></div>
        <div class="card-body">
          <div id="chart-top-genres"></div>
        </div>
      </div>

      {{-- Traffic Sources (placeholder; ganti kalau ada data) --}}
      <div class="card">
        <div class="card-header"><h4>Traffic Sources</h4></div>
        <div class="card-body">
          <div id="chart-traffic-sources"></div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
  {{-- ApexCharts core --}}
  <script src="{{ asset('assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
  <script>
    // Tracks Added (12 months)
    (function () {
      const el = document.querySelector('#chart-tracks-added');
      if (!el || typeof ApexCharts === 'undefined') return;
      const options = {
        chart: { type: 'area', height: 320, toolbar: { show: false } },
        dataLabels: { enabled: false },
        stroke: { width: 3 },
        series: [
          { name: 'Tracks', data: @json($seriesAdd) }
        ],
        xaxis: { categories: @json($labels), tooltip: { enabled: false } },
        yaxis: [{ labels: { formatter: (val) => `${val}` } }],
        tooltip: { shared: true },
      };
      new ApexCharts(el, options).render();
    })();

    // Top Genres (donut)
    (function () {
      const el = document.querySelector('#chart-top-genres');
      if (!el || typeof ApexCharts === 'undefined')) return;
      const options = {
        chart: { type: 'donut', height: 280 },
        series: @json($genreSeries),
        labels: @json($genreLabels),
        legend: { position: 'bottom' },
        noData: { text: 'No data' }
      };
      new ApexCharts(el, options).render();
    })();

    // Traffic Sources (placeholder statis — ganti ke data real kalau ada)
    (function () {
      const el = document.querySelector('#chart-traffic-sources');
      if (!el || typeof ApexCharts === 'undefined') return;
      const options = {
        chart: { type: 'pie', height: 280 },
        series: [44, 26, 18, 12],
        labels: ['Direct', 'Search', 'Referral', 'Social'],
        legend: { position: 'bottom' },
      };
      new ApexCharts(el, options).render();
    })();
  </script>
@endpush
