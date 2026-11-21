<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: orders (Guest Post Order Management with Client Price, Vendor Cost, and Profit)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('client_id');
            $table->date('order_date');
            $table->date('deadline')->nullable();
            $table->decimal('total_client_price', 10, 2);
            $table->decimal('total_vendor_cost', 10, 2);
            $table->decimal('total_profit', 10, 2);
            $table->enum('status', ['pending', 'in_progress', 'published', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
