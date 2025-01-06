<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitudesArriendo extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_arriendo'; // Nombre exacto de la tabla
    public $timestamps = true; // Habilitar timestamps

    protected $fillable = [
        'id',
        'rut_solicitante',
        'name_client', // Cambiado a snake_case
        'state',
        'cost',
        'date',
        'products',
    ];
}