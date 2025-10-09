@extends('layouts.master')
@section('title','FAQ Categories – Create')
@section('heading','Create Category')
@section('subheading','Tambah kategori FAQ baru.')
@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('faq-categories.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('faq-categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection