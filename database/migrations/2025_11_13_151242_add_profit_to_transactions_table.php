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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('profit', 15, 0)->nullable()->after('sellPrice');
            $table->decimal('commission', 15, 0)->nullable()->after('profit');
            $table->decimal('logistics', 15, 0)->nullable()->after('commission');
            $table->decimal('tax', 15, 0)->nullable()->after('logistics');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['profit', 'commission', 'logistics', 'tax']);
        });
    }
};
