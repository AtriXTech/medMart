<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_gateway_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')->constrained()->cascadeOnDelete();
            $table->string('gateway', 20);
            $table->string('code', 20);
            $table->timestamps();

            $table->unique(['bank_id', 'gateway']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_gateway_codes');
    }
};
