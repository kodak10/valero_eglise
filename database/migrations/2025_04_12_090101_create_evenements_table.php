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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description');
            $table->string('qr_code_path')->nullable(); // Peut être null au début
            $table->dateTime('date_evenement');
            $table->string('lieu')->nullable();
            $table->unsignedInteger('limite_scan_heure')->default(5); // Ex: 5h entre scans
            $table->unsignedInteger('nombre_scan_max')->default(2); // Max 2 scans par jour
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
