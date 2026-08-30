<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('pending')->after('status');
            $table->decimal('tax_amount', 10, 2)->default(0.00)->after('shipping_cost');
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('tax_amount');
            $table->string('currency', 3)->default('USD')->after('discount_amount');
            $table->string('tracking_number')->nullable()->after('payment_reference');
            $table->boolean('stock_restored')->default(false)->after('notes');
            $table->timestamp('paid_at')->nullable()->after('stock_restored');
            $table->timestamp('shipped_at')->nullable()->after('paid_at');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('delivered_at');
            $table->timestamp('refunded_at')->nullable()->after('cancelled_at');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['created_at']);
            $table->dropColumn([
                'payment_status',
                'tax_amount',
                'discount_amount',
                'currency',
                'tracking_number',
                'stock_restored',
                'paid_at',
                'shipped_at',
                'delivered_at',
                'cancelled_at',
                'refunded_at',
            ]);
        });
    }
};