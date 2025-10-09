@extends('layouts.master')
@section('title','FAQ Categories – Edit')
@section('heading','Edit Category')
@section('subheading','Perbarui kategori FAQ.')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('faq-categories.update', $cat) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text"
                 name="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $cat->name) }}"
                 required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="{{ route('faq-categories.index') }}" class="btn btn-outline-secondary">Back</a>
          <button class="btn btn-primary" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
