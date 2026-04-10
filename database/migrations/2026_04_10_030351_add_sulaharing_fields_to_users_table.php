<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable()->after('name');
            $table->string('fakultas')->nullable()->after('nim');
            $table->string('jurusan')->nullable()->after('fakultas');
            $table->enum('role', ['mahasiswa', 'pakar', 'admin'])->default('mahasiswa')->after('jurusan');
            $table->string('avatar_url')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('avatar_url');
            $table->timestamp('last_login')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'fakultas', 'jurusan', 'role', 'avatar_url', 'is_active', 'last_login']);
        });
    }
};
