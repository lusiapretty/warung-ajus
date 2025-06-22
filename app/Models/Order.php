<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelanggan',
        'no_meja',
        'tipe_pesanan',
        'pembayaran',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }   
}
