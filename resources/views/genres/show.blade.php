@extends('layouts.master')

@section('title', $genre->name . ' – Genre | BeatHive')
@section('heading', $genre->name)

@section('content')
<div class="page-content">
  <div class="card">
    <div class="card-body">
      <h5>Name</h5>
      <p>{{ $genre->name }}</p>

      <h5>Description</h5>
      <p>{{ $genre->description ?? '-' }}</p>

      <h5>Slug</h5>
      <p>{{ $genre->slug }}</p>

      <a href="{{ route('genres.edit', $genre) }}" class="btn btn-warning">Edit</a>
      <a href="{{ route('genres.index') }}" class="btn btn-light">Back</a>
    </div>
  </div>
</div>
@endsection
