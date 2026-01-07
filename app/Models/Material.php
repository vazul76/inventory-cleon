<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pengambilan()
    {
        return $this->hasMany(PengambilanMaterial::class);
    }

    public function isAvailable($jumlah = 1)
    {
        return $this->stock >= $jumlah;
    }

    public function kurangiStock($jumlah)
    {
        $this->decrement('stock', $jumlah);
    }

    public function tambahStock($jumlah)
    {
        $this->increment('stock', $jumlah);
    }
}