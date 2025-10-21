@extends('layouts.master')

@section('title', 'Edit Author – BeatHive')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Edit Author</h5>
      <a href="{{ route('author.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Back
      </a>
    </div>

    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('author.update', $author->id) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $author->name) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required value="{{ old('username', $author->username) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email', $author->email) }}">
          </div>

          {{-- Optional: change password --}}
          <div class="col-md-6">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
          </div>

          {{-- Status --}}
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', $author->status) ? 'checked' : '' }}>
              <label class="form-check-label" for="status">Active</label>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button class="btn btn-primary">
            <i class="bi bi-save"></i> Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
