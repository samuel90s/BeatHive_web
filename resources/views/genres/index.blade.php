@extends('layouts.master')

@section('title', 'Genres – BeatHive')
@section('heading', 'Genres')
@section('subheading', 'Kelola daftar genre musik.')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <h5 class="mb-0">Genre List</h5>
      <a href="{{ route('genres.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Add Genre
      </a>
    </div>
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Description</th>
              <th>Slug</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($genres as $g)
              <tr>
                <td><a href="{{ route('genres.show', $g) }}">{{ $g->name }}</a></td>
                <td>{{ $g->description ?? '-' }}</td>
                <td>{{ $g->slug }}</td>
                <td>
                  <a href="{{ route('genres.edit', $g) }}" class="btn btn-sm btn-warning">Edit</a>
                  <form action="{{ route('genres.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this genre?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center">No genres yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $genres->links() }}
    </div>
  </div>
</div>
@endsection
