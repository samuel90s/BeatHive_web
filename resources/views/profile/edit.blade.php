{{-- resources/views/profile/edit.blade.php --}}



@extends('layouts.master')





@section('title', 'My Account | BeatHive')


@section('heading', 'My Account')


@section('subheading', 'Kelola informasi akun Anda.')





@section('content')


<section class="section">


    <div class="row">


        <div class="col-12 col-lg-8">


            {{-- Form Update Profile Information --}}


            <div class="card">


                <div class="card-header">


                    <h5 class="card-title mb-0">Profile Information</h5>


                    <p class="text-muted small mb-0">Perbarui informasi profil dan alamat email akun Anda.</p>


                </div>


                <div class="card-body">


                    <form method="post" action="{{ route('profile.update') }}">


                        @csrf


                        @method('patch')





                        {{-- Avatar/Photo --}}


                        <div class="mb-3">


                            <label class="form-label">Avatar</label>


                            <div class="d-flex align-items-center">


                                @php


                                    // Membuat URL avatar dari inisial nama pengguna


                                    $userName = urlencode(auth()->user()->name);


                                    $avatarUrl = "https://ui-avatars.com/api/?name={$userName}&background=random&color=fff&size=80";


                                @endphp


                                <img src="{{ $avatarUrl }}" alt="Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">


                            </div>


                        </div>








                        {{-- Name --}}


                        <div class="mb-3">


                            <label for="name" class="form-label">Name</label>


                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required autofocus autocomplete="name">


                            @error('name')


                                <div class="invalid-feedback">{{ $message }}</div>


                            @enderror


                        </div>





                        {{-- Email --}}


                        <div class="mb-3">


                            <label for="email" class="form-label">Email</label>


                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required autocomplete="username">


                            @error('email')


                                <div class="invalid-feedback">{{ $message }}</div>


                            @enderror


                        </div>





                        <div class="d-flex align-items-center gap-3">


                            <button type="submit" class="btn btn-primary">Save Changes</button>





                            @if (session('status') === 'profile-updated')


                                <span class="text-success small">Saved.</span>


                            @endif


                        </div>


                    </form>


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


                    <p class="text-muted">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.</p>


                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">


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


                    <p>Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Harap masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.</p>





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


    // Jika ada error validasi hapus akun, otomatis buka lagi modalnya


    @if($errors->userDeletion->isNotEmpty())


    var errorModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));


    errorModal.show();


    @endif


});


</script>


@endpush