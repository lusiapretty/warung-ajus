<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'harga',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_addon');
    }
}
