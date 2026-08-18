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
        Schema::table('products', function (Blueprint $table) {
            // e.g. "M", "L" for size-variant drinks. Null when the product has no size.
            $table->string('size')->nullable()->after('stock');

            // How the product is sold/stocked: a single piece, or a sealed case/pack.
            $table->string('unit')->default('piece')->after('size');

            // Number of items per case/pack (e.g. 24 for a 24-can case). Null when unit is "piece".
            $table->unsignedInteger('pack_quantity')->nullable()->after('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['size', 'unit', 'pack_quantity']);
        });
    }
};
