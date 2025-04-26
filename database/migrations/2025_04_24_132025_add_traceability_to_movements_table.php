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
        Schema::table('movements', function (Blueprint $table) {
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete()->after('item_id');
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete()->after('from_location_id');
            $table->foreignId('from_box_id')->nullable()->constrained('storage_boxes')->nullOnDelete()->after('to_location_id');
            $table->foreignId('to_box_id')->nullable()->constrained('storage_boxes')->nullOnDelete()->after('from_box_id');
        });
    }

    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropForeign(['from_location_id']);
            $table->dropForeign(['to_location_id']);
            $table->dropForeign(['from_box_id']);
            $table->dropForeign(['to_box_id']);

            $table->dropColumn(['from_location_id', 'to_location_id', 'from_box_id', 'to_box_id']);
        });
    }
};
