<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sound_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->string('bg_color', 10)->default('#FFA500'); // optional UI
            $table->string('icon_path')->nullable();            // optional UI
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('sound_categories');
    }
};
