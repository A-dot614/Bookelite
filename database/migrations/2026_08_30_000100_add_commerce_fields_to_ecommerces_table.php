<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ecommerces', function (Blueprint $table) {
            $table->string('sku')->nullable()->after('isbn');
            $table->integer('low_stock_threshold')->default(5)->after('stock');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->string('status')->default('active')->after('is_active');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->string('seo_title')->nullable()->after('published_at');
            $table->text('seo_description')->nullable()->after('seo_title');
        });

        Schema::table('ecommerces', function (Blueprint $table) {
            $table->unique('sku');
            $table->index('category');
            $table->index('genre');
            $table->index('price');
            $table->index('seller_id');
            $table->index('created_at');
            $table->index(['is_active', 'status']);
            $table->index(['is_active', 'category']);
            $table->index(['is_active', 'seller_id']);
        });
    }

    public function down(): void
    {
        Schema::table('ecommerces', function (Blueprint $table) {
            $table->dropUnique(['sku']);
            $table->dropIndex(['category']);
            $table->dropIndex(['genre']);
            $table->dropIndex(['price']);
            $table->dropIndex(['seller_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['is_active', 'status']);
            $table->dropIndex(['is_active', 'category']);
            $table->dropIndex(['is_active', 'seller_id']);
            $table->dropColumn(['sku', 'low_stock_threshold', 'is_featured', 'status', 'published_at', 'seo_title', 'seo_description']);
        });
    }
};