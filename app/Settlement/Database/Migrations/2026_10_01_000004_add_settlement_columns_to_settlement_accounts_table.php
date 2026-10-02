<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->string('paystack_recipient_code')->nullable();
            $table->unsignedTinyInteger('name_match_score')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->index(['pharmacy_id', 'status'], 'settlement_accounts_pharmacy_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('settlement_accounts', function (Blueprint $table) {
            $table->dropIndex('settlement_accounts_pharmacy_status_idx');
            $table->dropColumn(['paystack_recipient_code', 'name_match_score', 'approved_at']);
        });
    }
};
