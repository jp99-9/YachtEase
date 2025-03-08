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
        Schema::create('storage_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name',30);
            $table->string('description',250);
            $table->integer('capacity');
            $table->foreignId('location_id')->constrained('locations')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storage_boxes');
    }
};
