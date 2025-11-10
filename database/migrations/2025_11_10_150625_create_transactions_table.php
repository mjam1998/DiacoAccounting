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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('bank_accounts_id')->nullable();
            $table->decimal('buyPrice',15,0);
            $table->decimal('sellPrice',15,0)->nullable();

            $table->boolean('isDebt')->default(false);
            $table->text('description')->nullable();
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('bank_accounts_id')->references('id')->on('bank_accounts');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
