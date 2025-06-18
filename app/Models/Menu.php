<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'harga',
        'kategori',
        'gambar'
    ];



    public function addons()
    {
        return $this->belongsToMany(Addon::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
