<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_session');
            $table->text('deskripsi')->nullable();
            $table->date('periode_awal');
            $table->date('periode_akhir');
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_sessions');
    }
};