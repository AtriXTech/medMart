<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->decimal('commission_rate', 5, 2)->nullable();
            $table->boolean('payout_hold')->default(false);
            $table->string('payout_hold_reason')->nullable();
            $table->timestamp('payout_hold_until')->nullable();
            $table->string('payout_schedule', 10)->default('daily');
            $table->unsignedSmallInteger('consecutive_payout_failures')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn([
                'commission_rate',
                'payout_hold',
                'payout_hold_reason',
                'payout_hold_until',
                'payout_schedule',
                'consecutive_payout_failures',
            ]);
        });
    }
};
