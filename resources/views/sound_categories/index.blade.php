@extends('layouts.master')

@section('title', 'Sound Categories – BeatHive')
@section('heading', 'Sound Categories')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Categories</h5>
      <a href="{{ route('sound_categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Category
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
              <th>Color</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $cat)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $cat->name }}</td>
                <td>{{ $cat->slug }}</td>
                <td><span class="badge" style="background: {{ $cat->bg_color }}">{{ $cat->bg_color }}</span></td>
                <td class="text-end">
                  <a href="{{ route('sound_categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('sound_categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">No categories found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $categories->links() }}
    </div>
  </div>
</div>
@endsection
