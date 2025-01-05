<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitudesArriendo extends Model
{
    use HasFactory;

    protected $fillable = [
        'rut_solicitante',
        'state',
        'cost',
        'date',
        'products',
    ];
}
