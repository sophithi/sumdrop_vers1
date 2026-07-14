<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'product_id')) {
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            if (!Schema::hasColumn('order_items', 'price')) {
                $table->decimal('price', 12, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'product_id', 'quantity', 'price']);
        });
    }
};