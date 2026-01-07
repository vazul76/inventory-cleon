<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function alats()
    {
        return $this->hasMany(Alat::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}