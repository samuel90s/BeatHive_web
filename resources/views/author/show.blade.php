@extends('layouts.master')

@section('title', 'Author Detail')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Author Detail</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('author.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left"></i> Back
        </a>
        <a href="{{ route('author.edit', $author->id) }}" class="btn btn-warning btn-sm">
          <i class="bi bi-pencil"></i> Edit
        </a>
        <form action="{{ route('author.destroy', $author->id) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Delete this author?')">
          @csrf @method('DELETE')
          <button class="btn btn-danger btn-sm">
            <i class="bi bi-trash"></i> Delete
          </button>
        </form>
      </div>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="row g-4">
        {{-- Left: avatar + status --}}
        <div class="col-md-3 text-center">
          @php
            $fallback = 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=random&color=fff&size=160';
            $avatar = $author->avatar
              ? asset('storage/'.ltrim($author->avatar, '/'))
              : $fallback;
          @endphp
          <img src="{{ $avatar }}" alt="Avatar" class="rounded-circle border mb-3" style="width: 120px; height: 120px; object-fit: cover;">
          <div class="d-grid gap-2">
            @if($author->status)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-light text-muted">Inactive</span>
            @endif

            @if($author->role === 1)
              <span class="badge bg-danger">Admin</span>
            @elseif($author->role === 2)
              <span class="badge bg-info text-dark">Author</span>
            @else
              <span class="badge bg-secondary">User</span>
            @endif
          </div>
        </div>

        {{-- Right: fields --}}
        <div class="col-md-9">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-muted">Full Name</label>
              <div class="fw-semibold">{{ $author->name }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Username</label>
              <div class="fw-semibold">{{ '@'.$author->username }}</div>
            </div>

            <div class="col-md-6">
              <label class="form-label text-muted">Email</label>
              <div>{{ $author->email }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Phone</label>
              <div>{{ $author->phone ?? '—' }}</div>
            </div>

            <div class="col-md-6">
              <label class="form-label text-muted">Created</label>
              <div>{{ optional($author->created_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>
            <div class="col-md-6">
              <label class="form-label text-muted">Last Login</label>
              <div>{{ optional($author->last_login_at)->format('Y-m-d H:i') ?? '—' }}</div>
            </div>

            <div class="col-12">
              <label class="form-label text-muted">Bio</label>
              <div class="border rounded p-2">{{ $author->bio ?: '—' }}</div>
            </div>

            @php
              $links = is_array($author->social_links) ? $author->social_links : (json_decode($author->social_links ?? '[]', true) ?: []);
            @endphp
            <div class="col-12">
              <label class="form-label text-muted">Social Links</label>
              @if(!empty($links))
                <ul class="mb-0">
                  @foreach($links as $key => $url)
                    @if($url)
                      <li class="small">
                        <span class="text-capitalize">{{ $key }}:</span>
                        <a href="{{ $url }}" target="_blank" rel="noopener">{{ $url }}</a>
                      </li>
                    @endif
                  @endforeach
                </ul>
              @else
                <div>—</div>
              @endif
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
