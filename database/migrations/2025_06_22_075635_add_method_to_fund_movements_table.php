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
        Schema::table('fund_movements', function (Blueprint $table) {
            $table->enum('method', ['transfer', 'card', 'paypal'])->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_movements', function (Blueprint $table) {
            $table->dropColumn('method');
        });
    }
};
