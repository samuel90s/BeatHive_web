<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add subcategory_id, play_count, download_count
     */
    public function up(): void
    {

        Schema::table('sound_effects', function (Blueprint $table) {

            // ================= SUBCATEGORY =================
            if (!Schema::hasColumn('sound_effects', 'subcategory_id')) {

                $table->foreignId('subcategory_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('sound_subcategories')
                    ->nullOnDelete();

            }

            // ================= PLAY COUNT =================
            if (!Schema::hasColumn('sound_effects', 'play_count')) {

                $table->unsignedInteger('play_count')
                    ->default(0)
                    ->after('is_active');

            }

            // ================= DOWNLOAD COUNT =================
            if (!Schema::hasColumn('sound_effects', 'download_count')) {

                $table->unsignedInteger('download_count')
                    ->default(0)
                    ->after('play_count');

            }

        });

        // ================= INDEX FOR POPULAR SORT =================
        $indexes = collect(DB::select("SHOW INDEX FROM sound_effects"))->pluck('Key_name');

        if (!$indexes->contains('sound_effects_play_count_index')) {

            Schema::table('sound_effects', function (Blueprint $table) {

                $table->index('play_count');

            });

        }

    }


    /**
     * Reverse migration
     */
    public function down(): void
    {

        Schema::table('sound_effects', function (Blueprint $table) {

            if (Schema::hasColumn('sound_effects', 'subcategory_id')) {
                $table->dropForeign(['subcategory_id']);
                $table->dropColumn('subcategory_id');
            }

            if (Schema::hasColumn('sound_effects', 'play_count')) {
                $table->dropColumn('play_count');
            }

            if (Schema::hasColumn('sound_effects', 'download_count')) {
                $table->dropColumn('download_count');
            }

        });

    }
};