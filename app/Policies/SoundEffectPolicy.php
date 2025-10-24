<?php

namespace App\Policies;

use App\Models\SoundEffect;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SoundEffectPolicy
{
    use HandlesAuthorization;

    /**
     * Jika user admin -> ijinkan semua
     * (sebelum checks lain)
     */
    public function before(User $user, $ability)
    {
        // Asumsi: role == 1 => admin. Jika beda, ubah kondisi ini.
        if ((int)$user->role === 1) {
            return true;
        }
    }

    /**
     * Siapa boleh melihat index/list
     * Kita batasi: admin (via before) atau author-role (role == 2) saja
     */
    public function viewAny(User $user)
    {
        // Asumsi: role == 2 => author (uploader). Ubah sesuai aplikasi kamu.
        return in_array((int)$user->role, [1,2]);
    }

    /**
     * Lihat single item (boleh semua authenticated user jika mau,
     * atau batasi ke admin/author)
     */
    public function view(User $user, SoundEffect $sound)
    {
        return in_array((int)$user->role, [1,2]) || $user->id === $sound->creator_user_id;
    }

    /**
     * siapa boleh membuat: authenticated author/admin
     */
    public function create(User $user)
    {
        return in_array((int)$user->role, [1,2]);
    }

    /**
     * update: hanya pemilik (creator) atau admin (admin ditangani by before)
     */
    public function update(User $user, SoundEffect $sound)
    {
        return $user->id === $sound->creator_user_id;
    }

    /**
     * delete: hanya pemilik (creator) atau admin (admin ditangani by before)
     */
    public function delete(User $user, SoundEffect $sound)
    {
        return $user->id === $sound->creator_user_id;
    }

    // optional: restore/forceDelete jika butuh
}
