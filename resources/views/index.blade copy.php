@extends('layouts.master')

@section('title', 'Dashboard – Stock Music')

@section('heading', 'Dashboard – Stock Music')
@section('subheading', 'Ringkasan performa marketplace BeatHive kamu.')

@section('content')
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
                <h5 class="mb-0 fw-bold">1,245</h5>
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
              <div class="bg-info text-white d-flex align-items-center justify-content-center rounded me-3" style="width:46px; height:46px;">
                <i class="bi bi-download fs-5"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">6,980</h5>
                <h6 class="mb-0 text-muted fs-6">Downloads (30d)</h6>
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
                <i class="bi bi-currency-dollar fs-5"></i>
              </div>
              <div>
                <h5 class="mb-0 fw-bold">125,450,000</h5>
                <h6 class="mb-0 text-muted fs-6">Revenue (IDR)</h6>
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
          <div class="card-header"><h4>Revenue & Downloads</h4></div>
          <div class="card-body">
            <div id="chart-revenue-downloads"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Latest Sales + Top Genres (tetap sama seperti file kamu) --}}
    <div class="row">
      <div class="col-12 col-xl-4">
        <div class="card">
          <div class="card-header"><h4>Top Genres</h4></div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div id="chart-top-genres"></div>
              </div>
            </div>
            <div class="row mt-3 g-3">
              <div class="col-6 d-flex align-items-center">
                <svg class="bi text-primary" width="32" height="32">
                  <use xlink:href="{{ asset('assets/static/images/bootstrap-icons.svg#circle-fill') }}" />
                </svg>
                <h6 class="mb-0 ms-3">Cinematic</h6>
              </div>
              <div class="col-6 text-end"><h6 class="mb-0">2,315 dl</h6></div>

              <div class="col-6 d-flex align-items-center">
                <svg class="bi text-success" width="32" height="32">
                  <use xlink:href="{{ asset('assets/static/images/bootstrap-icons.svg#circle-fill') }}" />
                </svg>
                <h6 class="mb-0 ms-3">Hip-Hop</h6>
              </div>
              <div class="col-6 text-end"><h6 class="mb-0">1,870 dl</h6></div>

              <div class="col-6 d-flex align-items-center">
                <svg class="bi text-danger" width="32" height="32">
                  <use xlink:href="{{ asset('assets/static/images/bootstrap-icons.svg#circle-fill') }}" />
                </svg>
                <h6 class="mb-0 ms-3">Lo-Fi</h6>
              </div>
              <div class="col-6 text-end"><h6 class="mb-0">1,246 dl</h6></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-header"><h4>Latest Sales</h4></div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover table-lg">
                <thead>
                  <tr>
                    <th>Buyer</th>
                    <th>Item</th>
                    <th>License</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="col-3">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-md"><img src="{{ asset('assets/compiled/jpg/5.jpg') }}" alt="avatar" /></div>
                        <p class="font-bold ms-3 mb-0">Studio Nusantara</p>
                      </div>
                    </td>
                    <td class="col-5"><p class="mb-0">"Sunset Vibes" – Lo-Fi</p></td>
                    <td class="col-2"><span class="badge bg-primary">Standard</span></td>
                    <td class="col-2"><strong>IDR 149,000</strong></td>
                  </tr>
                  <tr>
                    <td class="col-3">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-md"><img src="{{ asset('assets/compiled/jpg/2.jpg') }}" alt="avatar" /></div>
                        <p class="font-bold ms-3 mb-0">Citra Media</p>
                      </div>
                    </td>
                    <td class="col-5"><p class="mb-0">"Epic Horizon" – Cinematic</p></td>
                    <td class="col-2"><span class="badge bg-success">Broadcast</span></td>
                    <td class="col-2"><strong>IDR 2,400,000</strong></td>
                  </tr>
                  <tr>
                    <td class="col-3">
                      <div class="d-flex align-items-center">
                        <div class="avatar avatar-md"><img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="avatar" /></div>
                        <p class="font-bold ms-3 mb-0">PixelHub</p>
                      </div>
                    </td>
                    <td class="col-5"><p class="mb-0">"Street Bounce" – Hip-Hop</p></td>
                    <td class="col-2"><span class="badge bg-warning">Extended</span></td>
                    <td class="col-2"><strong>IDR 599,000</strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Right column --}}
  <div class="col-12 col-lg-3">
    <div class="card">
      <div class="card-body py-4 px-5">
        <div class="d-flex align-items-center">
          <div class="avatar avatar-xl"><img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="Face 1" /></div>
          <div class="ms-3 name">
            <h5 class="font-bold">BeatHive Admin</h5>
            <h6 class="text-muted mb-0">@beathive</h6>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h4>Recent Tickets</h4></div>
      <div class="card-content pb-4">
        <div class="recent-message d-flex px-4 py-3">
          <div class="avatar avatar-lg"><img src="{{ asset('assets/compiled/jpg/4.jpg') }}" alt="avatar" /></div>
          <div class="name ms-4">
            <h5 class="mb-1">Ardi Susanto</h5>
            <h6 class="text-muted mb-0">License question</h6>
          </div>
        </div>
        <div class="recent-message d-flex px-4 py-3">
          <div class="avatar avatar-lg"><img src="{{ asset('assets/compiled/jpg/5.jpg') }}" alt="avatar" /></div>
          <div class="name ms-4">
            <h5 class="mb-1">Dina Creative</h5>
            <h6 class="text-muted mb-0">Invoice request</h6>
          </div>
        </div>
        <div class="recent-message d-flex px-4 py-3">
          <div class="avatar avatar-lg"><img src="{{ asset('assets/compiled/jpg/1.jpg') }}" alt="avatar" /></div>
          <div class="name ms-4">
            <h5 class="mb-1">MotionLab</h5>
            <h6 class="text-muted mb-0">Track edit</h6>
          </div>
        </div>
        <div class="px-4">
          <a href="{{ route('inbox') }}" class="btn btn-block btn-xl btn-light-primary font-bold mt-3">Open Inbox</a>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><h4>Traffic Sources</h4></div>
      <div class="card-body">
        <div id="chart-traffic-sources"></div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  // Revenue & Downloads
  (function () {
      const el = document.querySelector('#chart-revenue-downloads');
      if (!el || typeof ApexCharts === 'undefined') return;
      const options = {
          chart: { type: 'line', height: 320, toolbar: { show: false } },
          stroke: { width: [3, 3] },
          series: [
              { name: 'Revenue (IDR juta)', data: [12, 10, 14, 18, 22, 25, 28, 30, 27, 29, 33, 35] },
              { name: 'Downloads', data: [380, 420, 510, 690, 720, 760, 820, 910, 860, 900, 980, 1050] }
          ],
          xaxis: { categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] },
          yaxis: [{ labels: { formatter: (val) => `${val}` } }],
          tooltip: { shared: true },
      };
      new ApexCharts(el, options).render();
  })();

  // Top Genres
  (function () {
      const el = document.querySelector('#chart-top-genres');
      if (!el || typeof ApexCharts === 'undefined') return;
      const options = {
          chart: { type: 'donut', height: 280 },
          series: [35, 28, 18, 10, 9],
          labels: ['Cinematic', 'Hip-Hop', 'Lo-Fi', 'EDM', 'Acoustic'],
          legend: { position: 'bottom' },
      };
      new ApexCharts(el, options).render();
  })();

  // Traffic Sources
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
