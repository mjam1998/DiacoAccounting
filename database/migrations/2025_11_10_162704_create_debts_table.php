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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('debt1',15,0);
            $table->date('debt1_time');
            $table->boolean('debt1_isPaid')->default(false);
            $table->decimal('debt2',15,0)->nullable();
            $table->date('debt2_time')->nullable();
            $table->boolean('debt2_isPaid')->default(false);
            $table->decimal('debt3',15,0)->nullable();
            $table->date('debt3_time')->nullable();
            $table->boolean('debt3_isPaid')->default(false);
            $table->decimal('debt4',15,0)->nullable();
            $table->date('debt4_time')->nullable();
            $table->boolean('debt4_isPaid')->default(false);
            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
