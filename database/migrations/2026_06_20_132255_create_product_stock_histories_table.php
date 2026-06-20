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
Schema::create('product_stock_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();

    $table->enum('type', ['in', 'out', 'adjust']);
    $table->integer('qty');

    $table->integer('before_stock');
    $table->integer('after_stock');

    $table->string('note')->nullable();
    $table->string('user_name')->nullable(); // siapa yg input

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_histories');
    }
};
