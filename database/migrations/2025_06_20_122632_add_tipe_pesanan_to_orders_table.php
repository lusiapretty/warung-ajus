<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('tipe_pesanan', ['dine_in', 'take_away'])
                  ->default('dine_in')
                  ->after('id')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       if (Schema::hasColumn('orders', 'tipe_pesanan')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('tipe_pesanan');
            });
        }
    }
};
