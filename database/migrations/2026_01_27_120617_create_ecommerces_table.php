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
        Schema::create('ecommerces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->nullable()->constrained('sellers')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('image_url');
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->string('category')->default('General');
            $table->string('genre')->nullable();
            $table->integer('stock')->default(10);
            $table->integer('pages')->nullable();
            $table->string('language')->default('English');
            $table->string('isbn')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ecommerces');
    }
};
