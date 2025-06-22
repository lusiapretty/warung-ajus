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
            $table->string('pembayaran')
                    ->default('belum_bayar')
                    ->after('no_meja')
                    ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       if (Schema::hasColumn('orders', 'pembayaran')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('pembayaran');
            });
        }
    }
};
