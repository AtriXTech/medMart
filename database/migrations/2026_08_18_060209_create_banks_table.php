<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->foreignId('bank_id')->nullable()->after('pharmacy_id')->constrained()->nullOnDelete();
            $table->dropColumn('bank_code');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
            $table->string('bank_code')->nullable();
        });
        
        Schema::dropIfExists('banks');
    }
};