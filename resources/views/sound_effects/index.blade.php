@extends('layouts.master')

@section('title', 'Sound Effects – BeatHive')
@section('heading', 'Sound Effects')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Sound Effects</h5>
      <a href="{{ route('sound_effects.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Sound Effect
      </a>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Title / Preview</th>
              <th>Author</th>
              <th>Category</th>
              <th>Duration</th>
              <th>Active</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sounds as $sound)
              <tr>
                <td>{{ $loop->iteration }}</td>

                <td>
                  <div class="d-flex align-items-center gap-2">
                    <strong>{{ $sound->title }}</strong>
                    @if($sound->preview_path && file_exists(public_path($sound->preview_path)))
                      <audio controls preload="none" style="width: 160px; height: 30px;">
                        <source src="{{ asset($sound->preview_path) }}" type="audio/mpeg">
                        Your browser does not support the audio tag.
                      </audio>
                    @else
                      <span class="text-muted small">(No preview)</span>
                    @endif
                  </div>
                </td>

                <td>{{ $sound->author->name ?? 'Unknown' }}</td>
                <td>{{ $sound->category->name ?? '-' }}</td>
                <td>{{ number_format($sound->duration_seconds, 2) }}s</td>
                <td>
                  @if($sound->is_active)
                    <span class="badge bg-success">Active</span>
                  @else
                    <span class="badge bg-secondary">Inactive</span>
                  @endif
                </td>

                <td class="text-end">
                  <a href="{{ route('sound_effects.edit', $sound->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('sound_effects.destroy', $sound->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this sound effect?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">No sound effects found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $sounds->links() }}
    </div>
  </div>
</div>
@endsection
