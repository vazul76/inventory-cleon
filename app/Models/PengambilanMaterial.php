<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengambilanMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'nama_pengambil',
        'jumlah',
        'tanggal_ambil',
        'keperluan',
        'lokasi_pemasangan',
    ];

    protected $casts = [
        'tanggal_ambil' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}