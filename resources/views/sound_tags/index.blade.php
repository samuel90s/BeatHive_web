@extends('layouts.master')

@section('title', 'Sound Tags – BeatHive')
@section('heading', 'Sound Tags')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Tags</h5>
      <a href="{{ route('sound_tags.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Tag
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
              <th>Name</th>
              <th>Slug</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tags as $tag)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td class="text-end">
                  <a href="{{ route('sound_tags.edit', $tag->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('sound_tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this tag?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">No tags found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $tags->links() }}
    </div>
  </div>
</div>
@endsection
