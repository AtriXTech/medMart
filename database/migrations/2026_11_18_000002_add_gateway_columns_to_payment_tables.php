<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('gateway', 20)->default('paystack')->after('reference');
            $table->string('gateway_reference')->nullable()->after('gateway');
            $table->index('gateway', 'payments_gateway_idx');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->string('gateway', 20)->default('paystack')->after('reference');
            $table->string('gateway_reference')->nullable()->after('gateway');
            $table->index('gateway', 'subscription_payments_gateway_idx');
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->string('gateway', 20)->nullable();
            $table->index('gateway', 'settlements_gateway_idx');
        });

        DB::table('payments')->whereNotNull('paystack_reference')->update(['gateway_reference' => DB::raw('paystack_reference')]);

        DB::table('settlements')
            ->where(function ($query) {
                $query->where('attempts', '>', 0)->orWhereNotNull('gateway_reference');
            })
            ->update(['gateway' => 'paystack']);
    }

    public function down(): void
    {
        Schema::table('settlements', function (Blueprint $table) {
            $table->dropIndex('settlements_gateway_idx');
            $table->dropColumn('gateway');
        });

        Schema::table('subscription_payments', function (Blueprint $table) {
            $table->dropIndex('subscription_payments_gateway_idx');
            $table->dropColumn(['gateway', 'gateway_reference']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex('payments_gateway_idx');
            $table->dropColumn(['gateway', 'gateway_reference']);
        });
    }
};
