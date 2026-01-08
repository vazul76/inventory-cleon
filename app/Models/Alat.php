<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'status',
        'available',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(PeminjamanAlat::class);
    }

    public function isAvailable($jumlah = 1)
    {
        return $this->available >= $jumlah;
    }

    public function kurangiAvailable($jumlah = 1)
    {
        $this->decrement('available', $jumlah);
        
        if ($this->available <= 0) {
            $this->update(['status' => 'borrowed']);
        }
    }

    public function tambahAvailable($jumlah = 1)
    {
        $this->increment('available', $jumlah);
        
        if ($this->available > 0) {
            $this->update(['status' => 'available']);
        }
    }

    public function peminjamanAktif()
    {
        return $this->peminjaman()
            ->where('status', 'dipinjam')
            ->get();
    }
}