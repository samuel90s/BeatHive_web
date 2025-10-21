@extends('layouts.master')

@section('title', 'Add Author – BeatHive')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Add Author</h5>
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

      <form method="POST" action="{{ route('author.store') }}">
        @csrf

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required value="{{ old('username') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>

          {{-- Status (active by default) --}}
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
              <label class="form-check-label" for="status">Active</label>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
          <button class="btn btn-primary">
            <i class="bi bi-save"></i> Create
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
