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
        Schema::create('bank_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bankAccount_id');
            $table->decimal('check_amount',15,0);
            $table->date('check_date');
            $table->text('description');

            $table->timestamps();
            $table->boolean('is_paid')->default(false);
            $table->foreign('bankAccount_id')->references('id')->on('bank_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_checks');
    }
};
