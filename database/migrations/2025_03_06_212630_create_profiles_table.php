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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('avatar')->nullable();
            $table->enum('status',['Active','Inactive'])->default('Inactive'); //debo tener en cuneta que este campo de estatus se activara o desactivara dependiendo de si esta la sesion de este profile activa enel dispositivo o no.
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('boat_id')->constrained('boats')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
