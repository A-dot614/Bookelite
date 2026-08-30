<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecommerce_id')->constrained('ecommerces')->nullOnDelete();
            $table->integer('quantity_change');
            $table->integer('previous_stock')->nullable();
            $table->integer('new_stock')->nullable();
            $table->string('reason');
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('ecommerce_id');
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};