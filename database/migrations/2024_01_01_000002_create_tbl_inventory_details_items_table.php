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
        Schema::create('tbl_inventory_details_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('tbl_inventory')->onDelete('cascade');
            $table->text('description');
            $table->integer('quantity');
            $table->string('rec_meter')->nullable();
            $table->integer('qty')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_inventory_details_items');
    }
};
