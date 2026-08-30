<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('isbn')->nullable()->after('author');
            $table->string('sku')->nullable()->after('isbn');
            $table->string('currency', 3)->default('USD')->after('line_total');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['isbn', 'sku', 'currency']);
        });
    }
};