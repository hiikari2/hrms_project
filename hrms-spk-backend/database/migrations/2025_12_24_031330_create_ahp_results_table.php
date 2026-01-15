<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('ahp_sessions')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('nilai_akhir', 10, 6);
            $table->integer('ranking');
            $table->string('rekomendasi');
            $table->json('detail_perhitungan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_results');
    }
};