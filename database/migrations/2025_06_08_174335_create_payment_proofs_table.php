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
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('proof_path');
            $table->string('status', 20)->default('pending');
            
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

             DB::statement("
            ALTER TABLE payment_proofs 
            ADD CONSTRAINT status_check 
            CHECK (status IN ('pending', 'verified', 'rejected'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
             DB::statement('ALTER TABLE payment_proofs DROP CONSTRAINT IF EXISTS status_check');
        Schema::dropIfExists('payment_proofs');
    }
};
