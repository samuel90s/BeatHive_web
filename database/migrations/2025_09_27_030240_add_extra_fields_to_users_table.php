<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role user
            $table->tinyInteger('role')
                ->default(3)
                ->after('password')
                ->comment('1=admin, 2=artist, 3=customer, 4=student');

            // Profil dasar
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('avatar')->nullable()->after('role'); // path foto profil
            $table->string('phone')->nullable()->after('avatar');

            // Artist spesifik
            $table->string('artist_name')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('artist_name');
            $table->json('social_links')->nullable()->after('bio'); // simpan array JSON

            // Student spesifik
            $table->string('student_id')->nullable()->after('social_links'); // NIM/Kartu mahasiswa

            // Sistem
            $table->tinyInteger('status')
                ->default(1)
                ->after('student_id')
                ->comment('1=active,0=suspended');
            $table->timestamp('last_login_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'username',
                'avatar',
                'phone',
                'artist_name',
                'bio',
                'social_links',
                'student_id',
                'status',
                'last_login_at',
            ]);
        });
    }
};
