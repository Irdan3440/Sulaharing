<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('disease_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_institusi')->nullable();
            $table->integer('guest_usia')->nullable();
            $table->boolean('is_guest')->default(false);
            $table->integer('total_score_bdi');
            $table->decimal('cf_result', 5, 4);
            $table->decimal('cf_percentage', 5, 1);
            $table->string('classification');
            $table->json('cf_detail')->nullable();
            $table->text('saran')->nullable();
            $table->timestamp('consulted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_results');
    }
};
