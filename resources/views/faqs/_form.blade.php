@php($editing = isset($faq))
<form action="{{ $editing ? route('faqs.update',$faq) : route('faqs.store') }}" method="POST" class="needs-validation" novalidate>
    @csrf
    @if($editing) @method('PUT') @endif


    <div class="mb-3">
        <label class="form-label">Question</label>
        <input type="text" name="question" class="form-control" required
            value="{{ old('question', $faq->question ?? '') }}" placeholder="Contoh: Bagaimana cara upload beat?">
        @error('question')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>


    <div class="mb-3">
        <label class="form-label">Answer</label>
        <textarea name="answer" rows="6" class="form-control" required
            placeholder="Tuliskan jawaban ringkas & jelas...">{{ old('answer', $faq->answer ?? '') }}</textarea>
        @error('answer')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>


    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Category</label>
            <select name="faq_category_id" class="form-select">
                <option value="">— None —</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('faq_category_id',$faq->faq_category_id ?? null)==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Order</label>
            <input type="number" min="0" name="order_column" class="form-control"
                value="{{ old('order_column', $faq->order_column ?? 0) }}">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_published" id="pub" {{ old('is_published', $faq->is_published ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="pub">Published</label>
            </div>
        </div>
    </div>


    <div class="mt-4 d-flex justify-content-end gap-2">
        <a href="{{ route('faqs.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button class="btn btn-primary">{{ $editing ? 'Update' : 'Create' }}</button>
    </div>
</form>