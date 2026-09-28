<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settlement_payments', function (Blueprint $table) {
            $table->foreignId('settlement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('payment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->primary(['settlement_id', 'payment_id']);

            $table->index('payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_payments');
    }
};