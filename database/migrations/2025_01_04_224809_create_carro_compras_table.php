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
        Schema::create('carro_compras', function (Blueprint $table) {
            $table->id();
            $table->string('rut_solicitante'); 
            $table->integer('id_product');
            $table->integer('quantity'); 
            $table->integer('days_rent'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carro_compras');
    }
};
