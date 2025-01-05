<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solicitudes_arriendo', function (Blueprint $table) {
            $table->id();
            $table->string('rut_solicitante');
            $table->string('state');
            $table->decimal('cost', 10, 2);
            $table->dateTime('date');
            $table->text('products');  // Almacenar los productos como JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes_arriendo');
    }
};
