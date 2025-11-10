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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->decimal('commission',5,2)->nullable();
            $table->decimal('tax',5,2)->nullable();
            $table->decimal('logistics',10,0)->nullable();
            $table->timestamps();
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
