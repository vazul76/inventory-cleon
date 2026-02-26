<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockSnapshot extends Model
{
    protected $fillable = [
        'tanggal',
        'total_alat_available',
        'total_material_stock',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}
