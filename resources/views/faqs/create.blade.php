@extends('layouts.master')
@section('title','FAQs – Create')
@section('heading','Create FAQ')
@section('subheading','Tambahkan pertanyaan & jawaban baru.')
@section('content')
<div class="page-content">
<div class="card">
<div class="card-body">
@include('faqs._form')
</div>
</div>
</div>
@endsection