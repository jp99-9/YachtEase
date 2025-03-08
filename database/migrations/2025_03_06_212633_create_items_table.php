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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('description',250);
            $table->integer('quantity');
            $table->string('image')->nullable();
            $table->string('brand',100)->nullable();
            $table->integer('minimum_recommended')->nullable();
            $table->string('qr_code')->nullable();
            $table->foreignId('type_id')->constrained('types')->onDelete('restrict');
            $table->foreignId('location_id')->constrained('locations')->onDelete('restrict');
            $table->foreignId('storage_box_id')->constrained('storage_boxes')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
