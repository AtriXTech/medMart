<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('gross_kobo')->nullable();
            $table->unsignedBigInteger('gateway_fee_kobo')->default(0);
            $table->unsignedBigInteger('platform_fee_kobo')->default(0);
            $table->bigInteger('net_kobo')->nullable();
            $table->string('gateway_fee_source', 20)->nullable();
            $table->string('settlement_status', 20)->nullable();
            $table->timestamp('eligible_at')->nullable();
            $table->string('hold_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->index(['pharmacy_id', 'settlement_status'], 'payments_pharmacy_settlement_idx');
            $table->index(['settlement_status', 'eligible_at'], 'payments_settlement_eligible_idx');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_pharmacy_settlement_idx');
            $table->dropIndex('payments_settlement_eligible_idx');
            $table->dropColumn([
                'gross_kobo',
                'gateway_fee_kobo',
                'platform_fee_kobo',
                'net_kobo',
                'gateway_fee_source',
                'settlement_status',
                'eligible_at',
                'hold_reason',
                'refunded_at',
            ]);
        });
    }
};
