<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('access_token', 64)->nullable()->unique()->after('order_code');
        });

        DB::table('orders')->whereNull('access_token')->orderBy('id')->eachById(function (object $order): void {
            do {
                $token = bin2hex(random_bytes(24));
            } while (DB::table('orders')->where('access_token', $token)->exists());

            DB::table('orders')->where('id', $order->id)->update(['access_token' => $token]);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique('orders_access_token_unique');
            $table->dropColumn('access_token');
        });
    }
};
