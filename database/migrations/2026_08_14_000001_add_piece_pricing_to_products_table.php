<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Optional per-piece price for a case product (e.g. price of a single can
            // from a 24-can case). Null means the case can't be broken up for sale.
            $table->decimal('price_khr_piece', 10, 2)->nullable()->after('pack_quantity');
            $table->decimal('price_usd_piece', 10, 2)->nullable()->after('price_khr_piece');
        });

        // Stock is now always tracked in the smallest sellable unit (pieces), so a case
        // product can be sold whole or broken up from the same pool. Previously `stock`
        // held a count of cases for case-unit products — convert it to base units once.
        DB::table('products')
            ->where('unit', 'case')
            ->whereNotNull('pack_quantity')
            ->where('pack_quantity', '>', 0)
            ->update([
                'stock' => DB::raw('stock * pack_quantity'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('products')
            ->where('unit', 'case')
            ->whereNotNull('pack_quantity')
            ->where('pack_quantity', '>', 0)
            ->update([
                'stock' => DB::raw('FLOOR(stock / pack_quantity)'),
            ]);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_khr_piece', 'price_usd_piece']);
        });
    }
};
