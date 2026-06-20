<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('invoice');
        $table->integer('total');
        $table->integer('money')->nullable();        // uang customer
        $table->integer('change_money')->nullable(); // kembalian
        $table->string('kasir_name');
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
