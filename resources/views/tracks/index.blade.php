{{-- resources/views/tracks/index.blade.php --}}
@extends('layouts.master')

@section('title', 'Tracks – List | BeatHive')
@section('heading', 'Tracks')
@section('subheading', 'Daftar music tracks dari database beathive.tracks.')

@push('head')
  <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
  <style>
    .filter-row .form-select, .filter-row .form-control { min-width: 160px; }
    .dt-length, .dt-info, .dt-paging { margin-top: .75rem; }
  </style>
@endpush

@section('content')
<section class="section">
  <div class="card">
    <div class="card-header">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="card-title mb-0">Tracks Table</h5>
        <div class="d-flex flex-wrap gap-2">
          {{-- Bulk import --}}
          <a href="{{ route('tracks.bulk.import') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-upload"></i> Bulk Import
          </a>
          {{-- Add track --}}
          <a href="{{ route('tracks.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Add Track
          </a>
        </div>
      </div>

      {{-- FILTER BAR: q, genre, published, per_page --}}
      <form method="GET" action="{{ route('tracks.index') }}" class="mt-3">
        <div class="row g-2 align-items-end filter-row">
          <div class="col-12 col-md">
            <label class="form-label">Search</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Title / Artist / Tags">
          </div>
          <div class="col-6 col-md-auto">
            <label class="form-label">Genre</label>
            <select name="genre" class="form-select">
              <option value="">All</option>
              @foreach($genres as $g)
                <option value="{{ $g->id }}" @selected($genreId == $g->id)>{{ $g->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-auto">
            <label class="form-label">Status</label>
            <select name="published" class="form-select">
              <option value="" @selected($published === null)>All</option>
              <option value="1" @selected($published === 1)>Published</option>
              <option value="0" @selected($published === 0)>Draft</option>
            </select>
          </div>
          <div class="col-6 col-md-auto">
            <label class="form-label">Per Page</label>
            <select name="per_page" class="form-select">
              @foreach([10,25,50,100,200] as $pp)
                <option value="{{ $pp }}" @selected($perPage == $pp)>{{ $pp }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6 col-md-auto">
            <button class="btn btn-outline-primary w-100"><i class="bi bi-filter"></i> Apply</button>
          </div>
          @if($q || $genreId || $published !== null || ($perPage ?? 50) != 50)
            <div class="col-6 col-md-auto">
              <a href="{{ route('tracks.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-x-circle"></i> Reset
              </a>
            </div>
          @endif
        </div>
      </form>
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
              <th class="d-none">CreatedAt</th> {{-- hidden for sort --}}
            </tr>
          </thead>
          <tbody>
            @forelse($tracks as $track)
              <tr>
                {{-- Cover --}}
                <td style="width:70px">
                  @php
                    $cover = $track->cover_path && Storage::disk('public')->exists($track->cover_path)
                      ? asset('storage/'.$track->cover_path)
                      : asset('assets/compiled/svg/favicon.svg');
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

                {{-- Genre --}}
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

                {{-- Status + toggle --}}
                <td>
                  <button
                    type="button"
                    class="btn btn-sm publish-toggle {{ $track->is_published ? 'btn-success' : 'btn-outline-secondary' }}"
                    data-id="{{ $track->id }}"
                    data-url="{{ route('tracks.publish', $track) }}"
                    title="Toggle publish">
                    {{ $track->is_published ? 'Published' : 'Draft' }}
                  </button>
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
                    <form action="{{ route('tracks.destroy', $track) }}" method="POST"
                          onsubmit="return confirm('Delete this track?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" title="Delete">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>

                  {{-- Optional: preview audio --}}
                  @if($track->preview_path && Storage::disk('public')->exists($track->preview_path))
                    <div class="mt-2">
                      <audio src="{{ asset('storage/'.$track->preview_path) }}" controls preload="none" style="width:160px"></audio>
                    </div>
                  @endif
                </td>

                {{-- CreatedAt (hidden for sorting) --}}
                <td class="d-none">{{ optional($track->created_at)->format('Y-m-d H:i:s') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="12" class="text-center text-muted py-4">
                  Belum ada data. <a href="{{ route('tracks.create') }}">Tambah track sekarang</a>.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Saat pakai DataTables client-side, biasanya pagination Laravel tidak perlu.
           Kalau mau tetap tampil, hapus d-none di bawah. --}}
      @if(method_exists($tracks, 'links'))
        <div class="mt-3 d-none">
          {{ $tracks->links() }}
        </div>
      @endif
    </div>
  </div>
</section>
@endsection

@push('scripts')
  <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
  <script>
    $(function () {
      // DataTables
      $('#tracks-table').DataTable({
        order: [[11, 'desc']], // sort by hidden CreatedAt desc
        columnDefs: [
          { targets: [11], visible: false },            // hide CreatedAt
          { orderable: false, targets: [0, 10] }        // disable sort Cover & Actions
        ],
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100]
      });

      // Toggle publish via AJAX (PATCH /tracks/{id}/publish)
      $(document).on('click', '.publish-toggle', function () {
        const btn = $(this);
        const url = btn.data('url');

        btn.prop('disabled', true);

        fetch(url, {
          method: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          }
        })
        .then(res => res.json())
        .then(json => {
          const isPublished = !!json.is_published;
          btn.toggleClass('btn-success', isPublished);
          btn.toggleClass('btn-outline-secondary', !isPublished);
          btn.text(isPublished ? 'Published' : 'Draft');
        })
        .catch(() => {
          alert('Gagal toggle publish. Coba lagi.');
        })
        .finally(() => {
          btn.prop('disabled', false);
        });
      });
    });
  </script>
@endpush
