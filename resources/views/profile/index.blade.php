{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.master')

@section('title', 'My Account | BeatHive')
@section('heading', 'My Account')
@section('subheading', 'Manage your profile, security, and account preferences.')

@section('content')
<section class="section">
  <div class="row">
    <div class="col-12 col-lg-8 order-2 order-lg-1">
      <div class="card">
        <div class="card-header pb-0">
          <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="tab-profile" data-bs-toggle="tab" data-bs-target="#pane-profile" type="button" role="tab" aria-controls="pane-profile" aria-selected="true">
                <i class="bi bi-person-circle me-1"></i> Profile
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="tab-security" data-bs-toggle="tab" data-bs-target="#pane-security" type="button" role="tab" aria-controls="pane-security" aria-selected="false">
                <i class="bi bi-shield-lock me-1"></i> Security
              </button>
            </li>
          </ul>
        </div>

        <div class="card-body">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="tab-content">
            {{-- ================= Profile Tab ================= --}}
            <div class="tab-pane fade show active" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">

              <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-3">
                @csrf
                @method('PATCH')

                {{-- Avatar uploader --}}
                <div class="mb-4">
                  <label class="form-label text-muted">Profile Picture</label>
                  <div class="d-flex align-items-center gap-3">
                    @php
                      $user = auth()->user();
                      $fallback = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random&color=fff&size=120';
                      $avatar = method_exists($user,'getAvatarUrlAttribute')
                        ? $user->avatar_url
                        : ($user->avatar ? asset('storage/'.ltrim($user->avatar, '/')) : $fallback);
                    @endphp

                    <img id="avatarPreview" src="{{ $avatar }}" alt="Avatar" class="rounded-circle border" style="width: 96px; height: 96px; object-fit: cover;">

                    <div class="flex-grow-1">
                      <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept=".jpg,.jpeg,.png,.webp">
                      @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                      <small class="text-muted d-block mt-1">JPG/PNG/WEBP • Max 2MB. Square image looks best.</small>

                      @if($user->avatar)
                        <div class="form-check mt-2">
                          <input class="form-check-input" type="checkbox" value="1" id="remove_avatar" name="remove_avatar">
                          <label class="form-check-label" for="remove_avatar">
                            Remove current picture
                          </label>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Full Name</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                         value="{{ old('name', $user->name) }}" placeholder="Your full name">
                  @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Display Name --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Display Name</label>
                  <input type="text" name="display_name" class="form-control @error('display_name') is-invalid @enderror"
                         value="{{ old('display_name', $user->display_name) }}" placeholder="Artist/Author display name">
                  @error('display_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Username --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Username</label>
                  <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                         value="{{ old('username', $user->username) }}" placeholder="letters, numbers, - _">
                  @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Email (readonly or editable as you prefer) --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Email</label>
                  <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                         value="{{ old('email', $user->email) }}" placeholder="email@example.com">
                  @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                  @if(!$user->email_verified_at)
                    <small class="text-warning">Your email is not verified.</small>
                  @endif
                </div>

                {{-- Phone --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Phone</label>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                         value="{{ old('phone', $user->phone) }}" placeholder="+62 812 3456 7890">
                  @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Bio --}}
                <div class="mb-3">
                  <label class="form-label text-muted">Bio</label>
                  <textarea name="bio" rows="4" class="form-control @error('bio') is-invalid @enderror"
                            placeholder="Tell something about you...">{{ old('bio', $user->bio) }}</textarea>
                  @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Social Links (flat fields; backend akan serialize ke JSON kalau perlu) --}}
                @php
                  $links = is_array($user->social_links) ? $user->social_links : (json_decode($user->social_links ?? '[]', true) ?: []);
                @endphp
                <div class="mb-3">
                  <label class="form-label text-muted d-block">Social Links</label>
                  <div class="row g-2">
                    <div class="col-md-6">
                      <input type="url" name="social_links[website]" class="form-control"
                             value="{{ old('social_links.website', $links['website'] ?? '') }}"
                             placeholder="Website URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url" name="social_links[instagram]" class="form-control"
                             value="{{ old('social_links.instagram', $links['instagram'] ?? '') }}"
                             placeholder="Instagram URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url" name="social_links[youtube]" class="form-control"
                             value="{{ old('social_links.youtube', $links['youtube'] ?? '') }}"
                             placeholder="YouTube URL">
                    </div>
                    <div class="col-md-6">
                      <input type="url" name="social_links[soundcloud]" class="form-control"
                             value="{{ old('social_links.soundcloud', $links['soundcloud'] ?? '') }}"
                             placeholder="SoundCloud URL">
                    </div>
                  </div>
                  @error('social_links') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Changes
                  </button>
                </div>
              </form>
            </div>

            {{-- ================= Security Tab ================= --}}
            <div class="tab-pane fade" id="pane-security" role="tabpanel" aria-labelledby="tab-security">
              <form method="POST" action="{{ route('profile.password.update') }}" class="mt-3">
                @csrf
                @method('PUT')

                <div class="mb-3">
                  <label class="form-label text-muted">Current Password</label>
                  <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required>
                  @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label text-muted">New Password</label>
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="At least 6 characters" required>
                  @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                  <label class="form-label text-muted">Confirm New Password</label>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required>
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn btn-outline-primary">
                    <i class="bi bi-key"></i> Update Password
                  </button>
                </div>
              </form>

              <hr class="my-4">

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label text-muted">Last login</label>
                  <div>{{ optional($user->last_login_at)->format('Y-m-d H:i') ?? '—' }}</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted">Last login IP</label>
                  <div>{{ $user->last_login_ip ?? '—' }}</div>
                </div>
              </div>
            </div>

          </div> {{-- tab-content --}}
        </div>
      </div>
    </div>

    {{-- ================= Right column: Danger Zone ================= --}}
    <div class="col-12 col-lg-4 order-1 order-lg-2">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Danger Zone</h5>
        </div>
        <div class="card-body">
          <p class="text-muted small">
            Deleting your account is permanent and will remove all your data.
          </p>
          <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">
            <i class="bi bi-trash"></i> Delete Account
          </button>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Delete Account Modal --}}
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
          <p>Please enter your password to confirm permanent deletion of your account.</p>
          <div class="mb-3">
            <label for="password-delete" class="form-label">Password</label>
            <input type="password" id="password-delete" name="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" autocomplete="current-password">
            @error('password', 'userDeletion') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
  // Show delete modal if validation errors exist on userDeletion bag
  @if($errors->userDeletion->isNotEmpty())
    var errorModal = new bootstrap.Modal(document.getElementById('confirm-user-deletion'));
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
