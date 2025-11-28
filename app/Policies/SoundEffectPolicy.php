<?php

namespace App\Policies;

use App\Models\SoundEffect;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoundEffectPolicy
{
    use HandlesAuthorization;

    /**
     * Admin: full akses semua ability.
     */
    public function before(User $user, $ability)
    {
        // role 1 = admin
        if ((int) $user->role === 1) {
            return true;
        }
    }

    /**
     * LIST / LIBRARY
     * Semua user login boleh lihat library.
     */
    public function viewAny(User $user): bool
    {
        return true; // udah lewat middleware auth
    }

    /**
     * DETAIL 1 SOUND EFFECT
     * Semua user login boleh lihat detail.
     */
    public function view(User $user, SoundEffect $sound): bool
    {
        return true;
    }

    /**
     * CREATE: hanya admin + author.
     */
    public function create(User $user): bool
    {
        return in_array((int) $user->role, [1, 2]);
    }

    /**
     * UPDATE: hanya creator (admin di-handle oleh before()).
     */
    public function update(User $user, SoundEffect $sound): bool
    {
        return $user->id === $sound->creator_user_id;
    }

    /**
     * DELETE: hanya creator (admin di-handle oleh before()).
     */
    public function delete(User $user, SoundEffect $sound): bool
    {
        return $user->id === $sound->creator_user_id;
    }
}
