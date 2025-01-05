<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarroCompras extends Model
{
    use HasFactory;
    protected $fillable = [
        'rut_solicitante',
        'id_product',
        'quantity',
        'days_rent',
    ];
    
}
