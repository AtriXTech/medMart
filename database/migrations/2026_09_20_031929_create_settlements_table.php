<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pharmacy_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('settlement_account_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('reference')->unique();

            $table->decimal('amount', 10, 2);

            $table->string('status')->default('pending');

            $table->string('gateway_reference')->nullable();

            $table->text('failure_reason')->nullable();

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index(['pharmacy_id', 'status']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlements');
    }
};