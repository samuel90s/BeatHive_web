{{-- resources/views/faqs/index.blade.php --}}
@extends('layouts.master')

@section('title', 'FAQs – BeatHive')
@section('heading', 'FAQs')
@section('subheading', 'Kelola pertanyaan yang sering ditanyakan.')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
      <h5 class="mb-0">FAQ List</h5>

      <div class="d-flex gap-2">
        {{-- Filter + Search --}}
        <form method="GET" action="{{ route('faqs.index') }}" class="d-flex gap-2">
          <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected(request('category')==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>

          <div class="input-group input-group-sm">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search question/answer...">
            <button class="btn btn-outline-secondary" type="submit">Go</button>
          </div>
        </form>

        <a href="{{ route('faqs.create') }}" class="btn btn-primary btn-sm">
          <i class="bi bi-plus"></i> Add FAQ
        </a>
      </div>
    </div>

    <div class="card-body">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($faqs->count())
        <div class="accordion" id="faqAcc">
          @foreach($faqs as $f)
            <div class="accordion-item shadow-sm mb-2 rounded-3 border-0">
              <h2 class="accordion-header" id="h{{ $f->id }}">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#c{{ $f->id }}"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                  <span class="me-2 badge {{ $f->is_published ? 'bg-success' : 'bg-secondary' }}">
                    {{ $f->is_published ? 'Published' : 'Draft' }}
                  </span>
                  <strong class="me-2">{{ $f->question }}</strong>
                  @if($f->category)
                    <span class="badge bg-light text-dark ms-auto">
                      <i class="bi bi-folder2-open me-1"></i>{{ $f->category->name }}
                    </span>
                  @endif
                </button>
              </h2>

              <div id="c{{ $f->id }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                   data-bs-parent="#faqAcc">
                <div class="accordion-body">
                  {!! nl2br(e($f->answer)) !!}

                  <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">Slug: {{ $f->slug }} · Order: {{ $f->order_column }}</small>

                    <div class="d-flex gap-2">
                      {{-- Reorder --}}
                      <form action="{{ route('faqs.reorder',$f) }}" method="POST" class="d-flex align-items-center gap-1">
                        @csrf @method('PATCH')
                        <input type="number" name="order_column" value="{{ $f->order_column }}"
                               class="form-control form-control-sm" style="width:90px" min="0">
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button>
                      </form>

                      {{-- Toggle publish --}}
                      <form action="{{ route('faqs.toggle',$f) }}" method="POST" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm {{ $f->is_published ? 'btn-outline-warning' : 'btn-outline-success' }}" type="submit">
                          {{ $f->is_published ? 'Set Draft' : 'Publish' }}
                        </button>
                      </form>

                      <a href="{{ route('faqs.edit',$f) }}" class="btn btn-sm btn-warning">Edit</a>

                      {{-- Delete --}}
                      <form action="{{ route('faqs.destroy',$f) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Delete this FAQ?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                      </form>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-3">
          {{ $faqs->links() }}
        </div>
      @else
        <div class="text-center text-muted py-4">No FAQs yet.</div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .accordion-button { border-radius: 1rem !important; }
  .accordion-item { background: #fff; }
  .accordion-button:not(.collapsed) { box-shadow: inset 0 -1px 0 rgba(0,0,0,.05); }
</style>
@endpush
