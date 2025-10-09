@extends('layouts.master')
@section('title','FAQ Categories – BeatHive')
@section('heading','FAQ Categories')
@section('subheading','Kelola kategori FAQ.')

@section('content')
@php
  // Taruh di satu tempat saja, jauh dari elemen HTML untuk menghindari parser error
  $curSort  = $sort  ?? request('sort','name');
  $curOrder = $order ?? request('order','asc');
  $curQ     = $q     ?? request('q');
@endphp

<div class="page-content">
  <div class="card">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
      <h5 class="mb-0">Category List</h5>

      <div class="d-flex gap-2 align-items-center">
        {{-- Filter & Sort --}}
        <form method="GET" action="{{ route('faq-categories.index') }}" class="d-flex gap-2">
          <div class="input-group input-group-sm" title="Search by name or slug">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" name="q" value="{{ $curQ }}" class="form-control" placeholder="Search name/slug...">
          </div>

          <select name="sort" class="form-select form-select-sm">
            <option value="name" @selected($curSort==='name')>Sort: Name</option>
            <option value="faqs_count" @selected($curSort==='faqs_count')>Sort: FAQs</option>
            <option value="created_at" @selected($curSort==='created_at')>Sort: Created</option>
          </select>

          <select name="order" class="form-select form-select-sm">
            <option value="asc"  @selected($curOrder==='asc')>Asc</option>
            <option value="desc" @selected($curOrder==='desc')>Desc</option>
          </select>

          <button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button>
        </form>

        <a href="{{ route('faq-categories.create') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus"></i> Add Category
        </a>
      </div>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($cats->count())
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th style="width:40%">Name</th>
                <th style="width:25%">Slug</th>
                <th style="width:15%">FAQs</th>
                <th style="width:20%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($cats as $c)
                <tr>
                  <td class="fw-semibold">{{ $c->name }}</td>
                  <td class="text-muted">{{ $c->slug }}</td>
                  <td>
                    <span class="badge bg-light text-dark">{{ $c->faqs_count ?? 0 }}</span>
                  </td>
                  <td>
                    <a href="{{ route('faq-categories.edit', $c) }}" class="btn btn-sm btn-warning">
                      <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('faq-categories.destroy', $c) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Delete this category?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $cats->links() }}
        </div>
      @else
        <div class="text-center py-5">
          <div class="mb-2">
            <i class="bi bi-folder2-open" style="font-size:2rem;opacity:.6"></i>
          </div>
          <p class="text-muted mb-3">No categories yet.</p>
          <a href="{{ route('faq-categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Create your first category
          </a>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .table td, .table th { vertical-align: middle; }
</style>
@endpush
