{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.master')

@section('title', 'My Account | BeatHive')
@section('heading', 'My Account')
@section('subheading', 'Kelola informasi akun Anda.')

@section('content')
@php
    /** @var \App\Models\User $user */
    $user = auth()->user();

    $fallback = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=120';

    // Jika punya kolom avatar di DB, gunakan itu, kalau tidak fallback ke UI Avatars
    $avatar = $user->avatar
        ? asset('storage/'.ltrim($user->avatar, '/'))
        : $fallback;
@endphp

<section class="section">
    <div class="row g-4">
        <div class="col-12 col-lg-8">
            {{-- Form Update Profile Information --}}
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h5 class="card-title mb-0">Profile Information</h5>
                    <p class="text-muted small mb-0">
                        Perbarui informasi profil dan alamat email akun Anda.
                    </p>
                </div>

                <div class="card-body pt-3">
                    @if(session('status') === 'profile-updated' || session('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') ?? 'Profil berhasil diperbarui.' }}
                        </div>
                    @endif

                    <form method="post"
                          action="{{ route('profile.update') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        {{-- Avatar --}}
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase small mb-2">Foto profil</h6>
                            <div class="d-flex align-items-center gap-3">
                                <img id="avatarPreview"
                                     src="{{ $avatar }}"
                                     alt="Avatar"
                                     class="rounded-circle border"
                                     style="width: 80px; height: 80px; object-fit: cover;">

                                <div class="flex-grow-1">
                                    <input type="file"
                                           name="avatar"
                                           id="avatar"
                                           class="form-control form-control-sm @error('avatar') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png,.webp">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <small class="text-muted d-block mt-1">
                                        JPG/PNG/WEBP • Maks 2MB • Disarankan gambar kotak.
                                    </small>

                                    @if($user->avatar)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   value="1"
                                                   id="remove_avatar"
                                                   name="remove_avatar">
                                            <label class="form-check-label" for="remove_avatar">
                                                Hapus foto profil saat ini
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                                autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(!$user->email_verified_at)
                                <small class="text-warning d-block mt-1">
                                    Email Anda belum terverifikasi.
                                </small>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>

                            @if (session('status') === 'profile-updated')
                                <span class="text-success small">Tersimpan.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Account Section --}}
        <div class="col-12 col-lg-4">
            <div class="card border-danger">
                <div class="card-header border-danger">
                    <h5 class="card-title mb-0 text-danger">Delete Account</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">
                        Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
                        Pastikan Anda sudah mengunduh data penting sebelum melanjutkan.
                    </p>
                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#confirm-user-deletion">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Modal Konfirmasi Hapus Akun --}}
<div class="modal fade"
     id="confirm-user-deletion"
     tabindex="-1"
     aria-labelledby="confirmUserDeletionLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                        Are you sure you want to delete your account?
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.
                        Harap masukkan kata sandi Anda untuk konfirmasi.
                    </p>

                    <div class="mb-3">
                        <label for="password-delete" class="form-label">Password</label>
                        <input
                            type="password"
                            id="password-delete"
                            name="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            autocomplete="current-password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Delete Account
                    </button>
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
        const errorModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));
        errorModal.show();
    @endif

    // Live preview avatar
    const input = document.getElementById('avatar');
    const preview = document.getElementById('avatarPreview');

    if (input && preview) {
        input.addEventListener('change', () => {
            const file = input.files && file ? file : input.files[0];
            if (!input.files || !input.files[0]) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        });
    }
});
</script>
@endpush
