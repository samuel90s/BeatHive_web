@extends('layouts.master')

@section('title', 'Sound Subcategories – BeatHive')
@section('heading', 'Sound Subcategories')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Subcategories</h5>
      <a href="{{ route('sound_subcategories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add Subcategory
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
              <th>Category</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($subs as $sub)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sub->name }}</td>
                <td>{{ $sub->slug }}</td>
                <td>{{ $sub->category->name ?? '-' }}</td>
                <td class="text-end">
                  <a href="{{ route('sound_subcategories.edit', $sub->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('sound_subcategories.destroy', $sub->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this subcategory?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted">No subcategories found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $subs->links() }}
    </div>
  </div>
</div>
@endsection
