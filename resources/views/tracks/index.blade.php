{{-- resources/views/tracks/index.blade.php --}}
@extends('master')

@section('title', 'Tracks – List | BeatHive')

@section('content')
<link rel="stylesheet" href="assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="./assets/compiled/css/table-datatable-jquery.css">

<div class="content-wrapper container">
  <div class="page-heading">
    <h3>Tracks</h3>
    <p class="text-muted">Daftar music tracks dari database <code>beathive.tracks</code>.</p>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Tracks Table</h5>
        <a href="{{ route('tracks.create') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus-circle"></i> Add Track
        </a>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped align-middle" id="tracks-table">
            <thead>
              <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Artist</th>
                <th>Genre</th>
                <th>BPM / Key</th>
                <th>Mood / Tags</th>
                <th>Price (IDR)</th>
                <th>Duration</th>
                <th>Release Date</th>
                <th>Status</th>
                <th>Actions</th>
                <th class="d-none">CreatedAt</th> {{-- kolom tersembunyi untuk sort --}}
              </tr>
            </thead>
            <tbody>
              @foreach($tracks as $track)
                <tr>
                  {{-- Cover --}}
                  <td style="width:70px">
                    @php
                      $cover = $track->cover_path ? asset('storage/'.$track->cover_path) : asset('assets/compiled/svg/favicon.svg');
                    @endphp
                    <img src="{{ $cover }}" alt="cover" class="rounded" style="width:56px;height:56px;object-fit:cover">
                  </td>

                  {{-- Title (link ke show) --}}
                  <td>
                    <div class="fw-semibold">
                      <a href="{{ route('tracks.show', $track) }}">{{ $track->title }}</a>
                    </div>
                    <small class="text-muted">{{ $track->slug }}</small>
                  </td>

                  {{-- Artist --}}
                  <td>{{ $track->artist }}</td>

                  {{-- Genre (relasi) --}}
                  <td>{{ optional($track->genre)->name ?? '-' }}</td>

                  {{-- BPM / Key --}}
                  <td>
                    <span>{{ $track->bpm ?? '-' }}</span>
                    <span class="text-muted">/</span>
                    <span>{{ $track->musical_key ?? '-' }}</span>
                  </td>

                  {{-- Mood / Tags --}}
                  <td>
                    <div>{{ $track->mood ?? '-' }}</div>
                    @if($track->tags)
                      <small class="text-muted">{{ $track->tags }}</small>
                    @endif
                  </td>

                  {{-- Price --}}
                  <td>
                    @if(!is_null($track->price_idr))
                      {{ number_format($track->price_idr, 0, ',', '.') }}
                    @else
                      -
                    @endif
                  </td>

                  {{-- Duration (detik -> mm:ss) --}}
                  <td>
                    @php
                      $dur = (int)($track->duration_seconds ?? 0);
                      $mm = floor($dur / 60);
                      $ss = str_pad($dur % 60, 2, '0', STR_PAD_LEFT);
                    @endphp
                    {{ $dur ? $mm.':'.$ss : '-' }}
                  </td>

                  {{-- Release Date --}}
                  <td>{{ $track->release_date?->format('Y-m-d') ?? '-' }}</td>

                  {{-- Status --}}
                  <td>
                    @if($track->is_published)
                      <span class="badge bg-success">Published</span>
                    @else
                      <span class="badge bg-secondary">Draft</span>
                    @endif
                  </td>

                  {{-- Actions --}}
                  <td>
                    <div class="d-flex gap-1">
                      <a href="{{ route('tracks.show', $track) }}" class="btn btn-sm btn-outline-secondary" title="View">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="{{ route('tracks.edit', $track) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form action="{{ route('tracks.destroy', $track) }}" method="POST" onsubmit="return confirm('Delete this track?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Delete">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                    {{-- link preview audio (opsional) --}}
                    @if($track->preview_path)
                      <div class="mt-1">
                        <audio src="{{ asset('storage/'.$track->preview_path) }}" controls preload="none" style="width:160px"></audio>
                      </div>
                    @endif
                  </td>

                  {{-- CreatedAt (hidden for sorting) --}}
                  <td class="d-none">{{ optional($track->created_at)->format('Y-m-d H:i:s') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- pagination fallback jika tidak pakai datatables server-side --}}
        @if(method_exists($tracks, 'links'))
          <div class="mt-3">
            {{ $tracks->links() }}
          </div>
        @endif
      </div>
    </div>
  </section>
</div>

{{-- JS --}}
<script src="assets/extensions/jquery/jquery.min.js"></script>
<script src="assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(function () {
    const table = $('#tracks-table').DataTable({
      order: [[11, 'desc']], // sort by hidden CreatedAt desc
      columnDefs: [
        { targets: [11], visible: false }, // hide CreatedAt
        { orderable: false, targets: [0,10] } // disable sort Cover & Actions
      ],
      pageLength: 10,
      lengthMenu: [10,25,50,100]
    });
  });
</script>
@endsection
