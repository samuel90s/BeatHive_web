@extends('layouts.master')

@section('title', 'Sound Subcategories – BeatHive')
@section('heading', 'Sound Subcategories')

@section('content')
<div class="page-content">
  <div class="card border-0 shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0">All Sound Subcategories</h5>
        <small class="text-muted">
          Group sound effects under more specific themes inside each main category.
        </small>
      </div>
      <a href="{{ route('sound_subcategories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Subcategory
      </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;">#</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Category</th>
              <th style="width: 120px;" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($subs as $sub)
              @php
                $cat     = $sub->category;
                $catName = $cat->name ?? '-';
                $catBg   = $cat->bg_color ?? '#E5E5E5';
              @endphp
              <tr>
                {{-- Row number dengan pagination aware --}}
                <td>
                  {{ $loop->iteration + ($subs->currentPage() - 1) * $subs->perPage() }}
                </td>

                {{-- Name + count --}}
                <td>
                  <div class="fw-semibold">{{ $sub->name }}</div>
                  <div class="small text-muted">
                    {{ $sub->sound_effects_count ?? 0 }} sounds
                  </div>
                </td>

                {{-- Slug --}}
                <td class="text-muted">
                  <code class="small">{{ $sub->slug }}</code>
                </td>

                {{-- Parent category badge --}}
                <td>
                  @if($cat)
                    <div class="d-inline-flex align-items-center gap-2 sfx-sub-cat-badge">
                      <span class="sfx-sub-cat-color" style="background: {{ $catBg }};"></span>
                      <span class="small">{{ $catName }}</span>
                    </div>
                  @else
                    <span class="text-muted small">—</span>
                  @endif
                </td>

                {{-- Actions --}}
                <td class="text-end">
                  <a href="{{ route('sound_subcategories.edit', $sub->id) }}"
                     class="btn btn-sm btn-outline-warning me-1">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <form action="{{ route('sound_subcategories.destroy', $sub->id) }}"
                        method="POST"
                        class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Delete this subcategory?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  No subcategories found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="mt-3">
        {{ $subs->links() }}
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .sfx-sub-cat-badge {
    padding: 2px 8px;
    border-radius: 999px;
    background: rgba(0,0,0,0.02);
  }
  .sfx-sub-cat-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 1px solid rgba(0,0,0,.1);
    flex-shrink: 0;
  }
</style>
@endpush
