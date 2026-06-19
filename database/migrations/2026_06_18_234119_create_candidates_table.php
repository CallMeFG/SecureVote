<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_period_id')->constrained('voting_periods')->onDelete('cascade');
            $table->string('nim'); // Dihapus unique() karena kandidat bisa daftar lagi di periode depan? Atau biarkan nim tetap non-unique di level global
            $table->string('name');
            $table->string('vice_nim')->nullable();
            $table->string('vice_name')->nullable();
            $table->text('vision');
            $table->text('mission');
            $table->string('photo_url')->nullable();
            $table->string('photo_path', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
