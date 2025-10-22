@extends('layouts.master')

@section('title', 'Sound Licenses – BeatHive')
@section('heading', 'Sound Licenses')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">All Licenses</h5>
      <a href="{{ route('sound_licenses.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg"></i> Add License
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
              <th>Price</th>
              <th>Description</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($licenses as $lic)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lic->name }}</td>
                <td>{{ $lic->price ? 'Rp '.number_format($lic->price, 0, ',', '.') : '-' }}</td>
                <td class="text-truncate" style="max-width: 380px;">
                  {{ Str::limit($lic->description, 120) }}
                </td>
                <td class="text-end">
                  <a href="{{ route('sound_licenses.edit', $lic->id) }}" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('sound_licenses.destroy', $lic->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this license?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center text-muted">No licenses found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $licenses->links() }}
    </div>
  </div>
</div>
@endsection
