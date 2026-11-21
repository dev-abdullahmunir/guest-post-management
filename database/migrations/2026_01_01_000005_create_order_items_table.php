<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: order_items (Individual guest post website items belonging to an order)
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('website_id');
            $table->unsignedBigInteger('vendor_id');
            $table->decimal('vendor_price', 10, 2);
            $table->decimal('client_price', 10, 2);
            $table->string('anchor_text');
            $table->string('target_url');
            $table->date('anchor_deadline')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'published', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('sellers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
