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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evenement_id')->constrained()->onDelete('cascade');
            $table->string('nom');
            $table->string('prenoms');
            $table->string('contact');
            $table->string('classe_metho');
            $table->boolean('est_invite')->default(false);
            $table->json('structures')->nullable();
            $table->integer('nombre_enfants')->default(0);
            $table->integer('nombre_invites')->default(0);
            $table->string('cookie_token')->nullable(); // pour identifier l'utilisateur avec cookies
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
