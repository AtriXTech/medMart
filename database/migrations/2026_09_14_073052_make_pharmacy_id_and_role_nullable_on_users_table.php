<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);

            $table->foreignId('pharmacy_id')
                ->nullable()
                ->change();

            $table->string('role')
                ->nullable()
                ->change();

            $table->foreign('pharmacy_id')
                ->references('id')
                ->on('pharmacies')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pharmacy_id']);

            $table->foreignId('pharmacy_id')
                ->nullable(false)
                ->change();

            $table->string('role')
                ->nullable(false)
                ->change();

            $table->foreign('pharmacy_id')
                ->references('id')
                ->on('pharmacies')
                ->cascadeOnDelete();
        });
    }
};