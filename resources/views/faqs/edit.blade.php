@extends('layouts.master')
@section('title','FAQs – Edit')
@section('heading','Edit FAQ')
@section('subheading','Perbarui konten FAQ.')
@section('content')
<div class="page-content">
<div class="card">
<div class="card-body">
@include('faqs._form', ['faq'=>$faq])
</div>
</div>
</div>
@endsection