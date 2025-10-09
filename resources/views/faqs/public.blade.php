{{-- resources/views/faqs/public.blade.php --}}
@extends('layouts.master')

@push('styles')
<style>
  .accordion-button { border-radius: 1rem !important; }
  .faq-item .accordion-button:focus { box-shadow: none; }
  .faq-item.highlight { outline: 2px solid rgba(0,0,0,.08); background: #f8f9fb; }
</style>
@endpush

@section('title', 'Help Center – FAQ')
@section('heading', 'Frequently Asked Questions')
@section('subheading', 'Temukan jawaban cepat untuk BeatHive.')

@section('content')
<div class="page-content">
  <div class="row g-3 mb-3">
    <div class="col-lg-8">
      <div class="input-group input-group-lg">
        <span class="input-group-text"><i class="bi bi-search"></i></span>
        <input id="faqSearch" class="form-control" placeholder="Cari topik, misal: upload, lisensi, pembayaran..." />
      </div>
    </div>
    <div class="col-lg-4">
      <select id="faqFilter" class="form-select form-select-lg">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  @foreach($categories as $cat)
    @if($cat->faqs->count())
    <div class="card mb-4 faq-cat" data-cat="{{ $cat->id }}">
      <div class="card-header bg-light d-flex align-items-center">
        <i class="bi bi-folder2-open me-2"></i>
        <strong>{{ $cat->name }}</strong>
      </div>
      <div class="card-body">
        <div class="accordion" id="acc-{{ $cat->id }}">
          @foreach($cat->faqs as $f)
            @php
              $qdata = strtolower($f->question.' '.strip_tags($f->answer));
            @endphp
            <div class="accordion-item rounded-3 border-0 shadow-sm mb-2 faq-item" data-q="{{ $qdata }}">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $f->id }}">
                  {{ $f->question }}
                </button>
              </h2>
              <div id="faq-{{ $f->id }}" class="accordion-collapse collapse">
                <div class="accordion-body">{!! nl2br(e($f->answer)) !!}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
  @endforeach
</div>
@endsection

@push('scripts')
<script>
  (function(){
    const s = document.getElementById('faqSearch');
    const f = document.getElementById('faqFilter');
    if(!s || !f) return;

    function apply(){
      const term = (s.value || '').toLowerCase().trim();
      const cat = f.value;
      document.querySelectorAll('.faq-cat').forEach(catCard=>{
        const catId = catCard.getAttribute('data-cat');
        let visibleAny = false;
        catCard.querySelectorAll('.faq-item').forEach(item=>{
          const q = item.getAttribute('data-q') || '';
          const matchTerm = !term || q.includes(term);
          const matchCat = !cat || catId === cat;
          const show = matchTerm && matchCat;
          item.style.display = show ? '' : 'none';
          item.classList.toggle('highlight', !!term && show);
          if(show) visibleAny = true;
        });
        catCard.style.display = visibleAny ? '' : 'none';
      });
    }
    s.addEventListener('input', apply);
    f.addEventListener('change', apply);
  })();
</script>
@endpush
