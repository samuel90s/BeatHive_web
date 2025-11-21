{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.master')

@section('title', 'My Account | BeatHive')
@section('heading', 'My Account')
@section('subheading', 'Manage your profile and account security.')

@section('content')
@php
  /** @var \App\Models\User $user */
  $user = auth()->user();

  $fallback = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=120';

  // Pakai kolom avatar saja, abaikan accessor
  $avatar = $user->avatar
      ? asset('storage/'.ltrim($user->avatar, '/'))
      : $fallback;

  $links = is_array($user->social_links)
      ? $user->social_links
      : (json_decode($user->social_links ?? '[]', true) ?: []);
@endphp


<section class="section">
  <div class="row g-4">
    {{-- Left: Profile & Security --}}
    <div class="col-12 col-lg-8 order-2 order-lg-1">
      <div class="card">
        <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title mb-0">Account Settings</h5>
            <small class="text-muted">Update your personal info and password.</small>
          </div>
        </div>

        <div class="card-body pt-3">
          @if(session('success'))
            <div class="alert alert-success mb-3">
              {{ session('success') }}
            </div>
          @endif

          {{-- Tabs --}}
          <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active"
                      id="tab-profile"
                      data-bs-toggle="tab"
                      data-bs-target="#pane-profile"
                      type="button"
                      role="tab"
                      aria-controls="pane-profile"
                      aria-selected="true">
                <i class="bi bi-person-circle me-1"></i> Profile
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link"
                      id="tab-security"
                      data-bs-toggle="tab"
                      data-bs-target="#pane-security"
                      type="button"
                      role="tab"
                      aria-controls="pane-security"
                      aria-selected="false">
                <i class="bi bi-shield-lock me-1"></i> Security
              </button>
            </li>
          </ul>

          <div class="tab-content">
            {{-- ================= Profile Tab ================= --}}
            <div class="tab-pane fade show active"
                 id="pane-profile"
                 role="tabpanel"
                 aria-labelledby="tab-profile">

              <form method="POST"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Avatar --}}
                <div class="mb-4">
                  <h6 class="text-muted text-uppercase small mb-2">Profile picture</h6>
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
                        JPG/PNG/WEBP • Max 2MB • Square image recommended.
                      </small>

                      @if($user->avatar)
                        <div class="form-check mt-2">
                          <input class="form-check-input"
                                 type="checkbox"
                                 value="1"
                                 id="remove_avatar"
                                 name="remove_avatar">
                          <label class="form-check-label" for="remove_avatar">
                            Remove current picture
                          </label>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

                {{-- Basic info --}}
                <div class="mb-3">
                  <h6 class="text-muted text-uppercase small mb-2">Basic information</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Full name</label>
                      <input type="text"
                             name="name"
                             class="form-control @error('name') is-invalid @enderror"
                             value="{{ old('name', $user->name) }}"
                             placeholder="Your full name">
                      @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Display name</label>
                      <input type="text"
                             name="display_name"
                             class="form-control @error('display_name') is-invalid @enderror"
                             value="{{ old('display_name', $user->display_name) }}"
                             placeholder="Artist / author name">
                      @error('display_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Username</label>
                      <input type="text"
                             name="username"
                             class="form-control @error('username') is-invalid @enderror"
                             value="{{ old('username', $user->username) }}"
                             placeholder="letters, numbers, - _">
                      @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                {{-- Contact --}}
                <div class="mb-3">
                  <h6 class="text-muted text-uppercase small mb-2">Contact</h6>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Email</label>
                      <input type="email"
                             name="email"
                             class="form-control @error('email') is-invalid @enderror"
                             value="{{ old('email', $user->email) }}"
                             placeholder="email@example.com">
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror

                      @if(!$user->email_verified_at)
                        <small class="text-warning d-block mt-1">
                          Your email is not verified.
                        </small>
                      @endif
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Phone</label>
                      <input type="text"
                             name="phone"
                             class="form-control @error('phone') is-invalid @enderror"
                             value="{{ old('phone', $user->phone) }}"
                             placeholder="+62 812 3456 7890">
                      @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                {{-- Bio --}}
                <div class="mb-3">
                  <h6 class="text-muted text-uppercase small mb-2">Bio</h6>
                  <textarea name="bio"
                            rows="3"
                            class="form-control @error('bio') is-invalid @enderror"
                            placeholder="Short description about you.">{{ old('bio', $user->bio) }}</textarea>
                  @error('bio')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                {{-- Social links --}}
                <div class="mb-4">
                  <h6 class="text-muted text-uppercase small mb-2">Social links</h6>
                  <div class="row g-2">
                    <div class="col-md-6">
                      <input type="url"
                             name="social_links[website]"
                             class="form-control"
                             value="{{ old('social_links.website', $links['website'] ?? '') }}"
                             placeholder="Website URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url"
                             name="social_links[instagram]"
                             class="form-control"
                             value="{{ old('social_links.instagram', $links['instagram'] ?? '') }}"
                             placeholder="Instagram URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url"
                             name="social_links[youtube]"
                             class="form-control"
                             value="{{ old('social_links.youtube', $links['youtube'] ?? '') }}"
                             placeholder="YouTube URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url"
                             name="social_links[soundcloud]"
                             class="form-control"
                             value="{{ old('social_links.soundcloud', $links['soundcloud'] ?? '') }}"
                             placeholder="SoundCloud URL">
                    </div>
                  </div>
                  @error('social_links')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Save changes
                  </button>
                </div>
              </form>
            </div>

            {{-- ================= Security Tab ================= --}}
            <div class="tab-pane fade"
                 id="pane-security"
                 role="tabpanel"
                 aria-labelledby="tab-security">

              <form method="POST"
                    action="{{ route('profile.password.update') }}"
                    class="mt-2">
                @csrf
                @method('PUT')

                <h6 class="text-muted text-uppercase small mb-3">Change password</h6>

                <div class="mb-3">
                  <label class="form-label">Current password</label>
                  <input type="password"
                         name="current_password"
                         class="form-control @error('current_password') is-invalid @enderror"
                         placeholder="••••••••"
                         required>
                  @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">New password</label>
                  <input type="password"
                         name="password"
                         class="form-control @error('password') is-invalid @enderror"
                         placeholder="At least 6 characters"
                         required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label">Confirm new password</label>
                  <input type="password"
                         name="password_confirmation"
                         class="form-control"
                         placeholder="Repeat new password"
                         required>
                </div>

                <div class="d-flex justify-content-end mb-3">
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-key me-1"></i> Update password
                  </button>
                </div>
              </form>

              <hr class="my-3">

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-0">Last login</label>
                  <div class="small">
                    {{ optional($user->last_login_at)->format('Y-m-d H:i') ?? '—' }}
                  </div>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-0">Last login IP</label>
                  <div class="small">
                    {{ $user->last_login_ip ?? '—' }}
                  </div>
                </div>
              </div>
            </div>
          </div> {{-- tab-content --}}
        </div>
      </div>
    </div>

    {{-- Right: Danger Zone --}}
    <div class="col-12 col-lg-4 order-1 order-lg-2">
      <div class="card border-danger">
        <div class="card-header border-danger d-flex align-items-center justify-content-between">
          <h6 class="card-title mb-0 text-danger">Danger zone</h6>
        </div>
        <div class="card-body">
          <p class="text-muted small mb-3">
            Deleting your account is permanent and cannot be undone. All your data will be removed.
          </p>
          <button type="button"
                  class="btn btn-outline-danger btn-sm"
                  data-bs-toggle="modal"
                  data-bs-target="#confirm-user-deletion">
            <i class="bi bi-trash me-1"></i> Delete account
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Delete Account Modal --}}
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
            Delete account
          </h5>
          <button type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <p class="mb-3">
            This action is permanent. Please enter your password to confirm.
          </p>
          <div class="mb-3">
            <label for="password-delete" class="form-label">Password</label>
            <input type="password"
                   id="password-delete"
                   name="password"
                   class="form-control @error('password','userDeletion') is-invalid @enderror"
                   autocomplete="current-password">
            @error('password','userDeletion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button"
                  class="btn btn-light"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-danger">
            Delete account
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
  // Show delete modal if validation errors exist on userDeletion bag
  @if($errors->userDeletion->isNotEmpty())
    const errorModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));
    errorModal.show();
  @endif

  // Avatar live preview
  const input = document.getElementById('avatar');
  const preview = document.getElementById('avatarPreview');
  if (input && preview) {
    input.addEventListener('change', () => {
      const file = input.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => { preview.src = e.target.result; };
      reader.readAsDataURL(file);
    });
  }
});
</script>
@endpush
