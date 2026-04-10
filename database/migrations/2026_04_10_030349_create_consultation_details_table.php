<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_result_id')->constrained()->cascadeOnDelete();
            $table->foreignId('symptom_id')->constrained()->cascadeOnDelete();
            $table->integer('answer_index');
            $table->integer('answer_score');
            $table->decimal('cf_user', 4, 2)->default(0);
            $table->decimal('cf_pakar_mb', 4, 2)->default(0);
            $table->decimal('cf_pakar_md', 4, 2)->default(0);
            $table->decimal('cf_combine', 5, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_details');
    }
};
