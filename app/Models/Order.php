<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'nama_pelanggan',
        'no_meja',
        'tipe_pesanan',
        'pembayaran',
        'status',
        'payment_status',
        'user_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }   

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function menu() 
    {
        return $this->belongsTo(Menu::class);
    }
}
