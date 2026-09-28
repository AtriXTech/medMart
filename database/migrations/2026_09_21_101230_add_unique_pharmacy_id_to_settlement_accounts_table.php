<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->unique('pharmacy_id');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->dropUnique(['pharmacy_id']);
        });
    }
};