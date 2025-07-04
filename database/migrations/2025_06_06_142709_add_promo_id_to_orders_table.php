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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('promo_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('grandtotal', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
             $table->dropForeign(['promo_id']);
            $table->dropColumn('promo_id');
            $table->dropColumn('grandtotal');
        });
    }
};
