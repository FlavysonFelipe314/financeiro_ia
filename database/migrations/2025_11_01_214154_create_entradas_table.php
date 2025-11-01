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
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');
            $table->foreignId('conta_id')
            ->constrained('contas')
            ->onDelete('CASCADE')
            ->onUpdate('CASCADE');
            $table->string('title', 255);
            $table->string('category', 255);
            $table->string('payment_method', 255); //pix, cartão, boleto...
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
