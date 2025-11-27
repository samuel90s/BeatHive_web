<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_package_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('package', ['basic', 'entrepreneur', 'professional'])->default('basic');
            $table->timestamp('package_activated_at')->nullable();
            $table->timestamp('package_expires_at')->nullable();
            $table->json('package_features')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'package', 
                'package_activated_at', 
                'package_expires_at',
                'package_features'
            ]);
        });
    }
};