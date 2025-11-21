@extends('layouts.master')

@section('title', 'Sound Categories – BeatHive')
@section('heading', 'Sound Categories')

@section('content')
<div class="page-content">
  <div class="card border-0 shadow-sm">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0">All Sound Categories</h5>
        <small class="text-muted">Manage color & icon used on the Sound Effects page.</small>
      </div>
      <a href="{{ route('sound_categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Category
      </a>
    </div>

    {{-- BODY --}}
    <div class="card-body">

      {{-- Success Alert --}}
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;">#</th>
              <th style="width: 60px;">Icon</th>
              <th>Name</th>
              <th>Slug</th>
              <th style="width: 140px;">Color</th>
              <th style="width: 120px;" class="text-end">Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse($categories as $cat)
              @php
                $bg   = $cat->bg_color ?: '#FFBEB5';
                $icon = $cat->icon_path
                          ? (str_starts_with($cat->icon_path, 'http')
                                ? $cat->icon_path
                                : asset($cat->icon_path))
                          : null;
              @endphp

              <tr>
                {{-- ROW NUMBER --}}
                <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>

                {{-- ICON PREVIEW --}}
                <td>
                  <div style="
                      width:32px;
                      height:32px;
                      border-radius:8px;
                      background: {{ $bg }};
                      display:flex;
                      align-items:center;
                      justify-content:center;
                      overflow:hidden;
                    ">
                    @if($icon)
                      <img src="{{ $icon }}"
                           alt="{{ $cat->name }}"
                           style="
                              width:20px;
                              height:20px;
                              max-width:20px;
                              max-height:20px;
                              object-fit:contain;
                              display:block;
                           ">
                    @else
                      <div style="
                          display:flex;
                          gap:2px;
                          width:20px;
                          height:20px;
                          align-items:flex-end;
                        ">
                        <span style="flex:1;height:30%;background:rgba(0,0,0,.3);border-radius:2px;"></span>
                        <span style="flex:1;height:70%;background:rgba(0,0,0,.3);border-radius:2px;"></span>
                        <span style="flex:1;height:50%;background:rgba(0,0,0,.3);border-radius:2px;"></span>
                        <span style="flex:1;height:65%;background:rgba(0,0,0,.3);border-radius:2px;"></span>
                      </div>
                    @endif
                  </div>
                </td>

                {{-- NAME --}}
                <td>
                  <div class="fw-semibold">{{ $cat->name }}</div>
                  <div class="small text-muted">
                    {{ $cat->sound_effects_count ?? 0 }} sounds
                  </div>
                </td>

                {{-- SLUG --}}
                <td class="text-muted">
                  <code class="small">{{ $cat->slug }}</code>
                </td>

                {{-- COLOR --}}
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <span style="
                        width:26px;
                        height:18px;
                        border-radius:6px;
                        border:1px solid rgba(255,255,255,.2);
                        background: {{ $bg }};
                        flex-shrink:0;
                      "></span>
                    <span class="small text-muted">{{ $cat->bg_color ?: '—' }}</span>
                  </div>
                </td>

                {{-- ACTION BUTTONS --}}
                <td class="text-end">
                  <a href="{{ route('sound_categories.edit', $cat->id) }}"
                     class="btn btn-sm btn-outline-warning me-1">
                    <i class="bi bi-pencil"></i>
                  </a>

                  <form action="{{ route('sound_categories.destroy', $cat->id) }}"
                        method="POST"
                        class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Delete this category?')">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </td>

              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  No categories found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- PAGINATION --}}
      <div class="mt-3">
        {{ $categories->links() }}
      </div>
    </div>

  </div>
</div>
@endsection
