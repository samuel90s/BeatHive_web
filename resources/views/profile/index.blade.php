{{-- resources/views/profile/index.blade.php --}}



@extends('layouts.master')





@section('title', 'My Account | BeatHive')


@section('heading', 'My Account')


@section('subheading', 'Lihat informasi profil Anda.')





@section('content')


<section class="section">


    <div class="row">


        <div class="col-12 col-lg-8">


            <div class="card">


                <div class="card-header d-flex justify-content-between align-items-center">


                    <h5 class="card-title mb-0">Profile Information</h5>


                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">


                        <i class="bi bi-pencil-square"></i> Edit Profile


                    </a>


                </div>


                <div class="card-body">


                    {{-- Avatar --}}


                    <div class="mb-4">


                        <label class="form-label text-muted">Avatar</label>


                        <div class="d-flex align-items-center">


                            @php


                                $userName = urlencode(auth()->user()->name);


                                $avatarUrl = "https://ui-avatars.com/api/?name={$userName}&background=random&color=fff&size=80";


                            @endphp


                            <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">


                        </div>


                    </div>





                    {{-- Name --}}


                    <div class="mb-3">


                        <label class="form-label text-muted">Name</label>


                        <p class="fs-5">{{ auth()->user()->name }}</p>


                    </div>





                    {{-- Email --}}


                    <div class="mb-3">


                        <label class="form-label text-muted">Email</label>


                        <p class="fs-5">{{ auth()->user()->email }}</p>


                    </div>


                </div>


            </div>


        </div>





        <div class="col-12 col-lg-4">


            {{-- Delete Account Section --}}


            <div class="card">


                <div class="card-header">


                    <h5 class="card-title mb-0">Delete Account</h5>


                </div>


                <div class="card-body">


                    <p class="text-muted small">Setelah akun Anda dihapus, semua datanya akan dihapus secara permanen.</p>


                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">


                        Delete Account


                    </button>


                </div>


            </div>


        </div>


    </div>


</section>





<!-- Modal Konfirmasi Hapus Akun -->


<div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">


    <div class="modal-dialog">


        <div class="modal-content">


            <form method="post" action="{{ route('profile.destroy') }}">


                @csrf


                @method('delete')


                <div class="modal-header">


                    <h5 class="modal-title" id="confirmUserDeletionLabel">Are you sure you want to delete your account?</h5>


                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>


                </div>


                <div class="modal-body">


                    <p>Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.</p>


                    <div class="mb-3">


                        <label for="password-delete" class="form-label">Password</label>


                        <input type="password" id="password-delete" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" autocomplete="current-password">


                         @error('password', 'userDeletion')


                            <div class="invalid-feedback">{{ $message }}</div>


                        @enderror


                    </div>


                </div>


                <div class="modal-footer">


                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>


                    <button type="submit" class="btn btn-danger">Delete Account</button>


                </div>


            </form>


        </div>


    </div>


</div>


@endsection





@push('scripts')


<script>


document.addEventListener('DOMContentLoaded', function () {


    @if($errors->userDeletion->isNotEmpty())


    var errorModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));


    errorModal.show();


    @endif


});


</script>


@endpush