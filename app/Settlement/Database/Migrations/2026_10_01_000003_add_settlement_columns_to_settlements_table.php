<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasUniqueReference = collect(Schema::getIndexes('settlements'))->contains(
            static fn (array $index): bool => ($index['unique'] ?? false) && ($index['columns'] ?? []) === ['reference']
        );

        Schema::table('settlements', function (Blueprint $table) use ($hasUniqueReference) {
            $table->unsignedBigInteger('gross_kobo')->default(0);
            $table->unsignedBigInteger('fee_kobo')->default(0);
            $table->bigInteger('adjustment_kobo')->default(0);
            $table->bigInteger('net_kobo')->default(0);
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('initiated_at')->nullable();
            $table->string('hold_reason')->nullable();

            $table->index(['status', 'last_attempt_at'], 'settlements_status_attempt_idx');
            $table->index(['pharmacy_id', 'status'], 'settlements_pharmacy_status_idx');

            if (! $hasUniqueReference) {
                $table->unique('reference', 'settlements_reference_unique');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settlements', function (Blueprint $table) {
            $table->dropIndex('settlements_status_attempt_idx');
            $table->dropIndex('settlements_pharmacy_status_idx');
            $table->dropColumn([
                'gross_kobo',
                'fee_kobo',
                'adjustment_kobo',
                'net_kobo',
                'attempts',
                'last_attempt_at',
                'initiated_at',
                'hold_reason',
            ]);
        });
    }
};
