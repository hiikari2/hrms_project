<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('ahp_sessions')->onDelete('cascade');
            $table->foreignId('kriteria_a_id')->constrained('kriteria')->onDelete('cascade');
            $table->foreignId('kriteria_b_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai_perbandingan', 5, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
    }
};