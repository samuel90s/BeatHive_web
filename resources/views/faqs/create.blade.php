@extends('layouts.master')

@section('title','FAQs – Create')
@section('heading','Create FAQ')
@section('subheading','Tambah pertanyaan & jawaban baru.')

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <form method="POST" action="{{ route('faqs.store') }}">
        @csrf

        <div class="row g-3">
          <div class="col-lg-8">
            <div class="mb-3">
              <label class="form-label">Question</label>
              <input type="text" name="question"
                     class="form-control @error('question') is-invalid @enderror"
                     value="{{ old('question') }}" required>
              @error('question')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Answer</label>
              <textarea name="answer" rows="6"
                        class="form-control @error('answer') is-invalid @enderror"
                        required>{{ old('answer') }}</textarea>
              @error('answer')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="col-lg-4">
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select name="faq_category_id" class="form-select">
                <option value="">— No category —</option>
                @foreach($categories as $c)
                  <option value="{{ $c->id }}" @selected(old('faq_category_id')==$c->id)>{{ $c->name }}</option>
                @endforeach
              </select>
              @error('faq_category_id')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Order</label>
              <input type="number" name="order_column" class="form-control"
                     value="{{ old('order_column', 0) }}" min="0">
              @error('order_column')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>

            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                     @checked(old('is_published'))>
              <label class="form-check-label" for="is_published">Publish now</label>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
          <a href="{{ route('faqs.index') }}" class="btn btn-outline-secondary">Cancel</a>
          <button class="btn btn-primary" type="submit">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
