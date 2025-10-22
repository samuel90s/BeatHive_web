<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('sound_effects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();

            // file & path
            $table->string('file_path');
            $table->enum('file_ext', ['wav','mp3','ogg','flac','aiff']);
            $table->string('mime_type', 80)->nullable();
            $table->unsignedBigInteger('size_bytes'); // size_mb akan digenerate

            // metadata (diisi otomatis via ffprobe)
            $table->decimal('duration_seconds', 8, 3);
            $table->integer('sample_rate')->nullable();   // Hz
            $table->tinyInteger('channels')->nullable();  // 1,2,...
            $table->integer('bitrate_kbps')->nullable();  // kbps
            $table->tinyInteger('bit_depth')->nullable(); // 16/24/32

            // optional analitik audio
            $table->decimal('loudness_lufs', 6, 2)->nullable();
            $table->decimal('peak_dbfs', 6, 2)->nullable();

            // assets turunan
            $table->string('preview_path')->nullable();    // mp3 128kbps
            $table->string('waveform_image')->nullable();  // png
            $table->string('fingerprint', 64)->nullable(); // sha1/md5 untuk duplikat

            // klasifikasi & lisensi
            $table->foreignId('category_id')->nullable()->constrained('sound_categories')->nullOnDelete();
            $table->foreignId('license_type_id')->nullable()->constrained('sound_licenses')->nullOnDelete();

            // author dari users
            $table->foreignId('creator_user_id')->nullable()->constrained('users')->nullOnDelete();

            // stat
            $table->unsignedInteger('play_count')->default(0);
            $table->unsignedInteger('download_count')->default(0);
            $table->boolean('is_active')->default(true);

            $table->enum('analysis_status', ['pending','processing','done','failed'])->default('pending');

            $table->timestamps();

            // index yang sering dipakai
            $table->index(['category_id','is_active']);
            $table->index(['creator_user_id']);
            $table->index(['fingerprint']);
        });

        // GENERATED COLUMN size_mb
        DB::statement("ALTER TABLE sound_effects
            ADD COLUMN size_mb DECIMAL(10,2) AS (ROUND(size_bytes / 1048576, 2)) STORED AFTER size_bytes");
    }

    public function down(): void {
        Schema::dropIfExists('sound_effects');
    }
};
