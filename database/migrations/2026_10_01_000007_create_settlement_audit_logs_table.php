<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settlement_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action', 80)->index();
            $table->unsignedBigInteger('pharmacy_id')->nullable()->index();
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('actor_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settlement_audit_logs');
    }
};
