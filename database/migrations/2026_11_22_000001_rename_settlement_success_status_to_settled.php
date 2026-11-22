<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settlements')) {
            DB::table('settlements')->where('status', 'success')->update(['status' => 'settled']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settlements')) {
            DB::table('settlements')->where('status', 'settled')->update(['status' => 'success']);
        }
    }
};
