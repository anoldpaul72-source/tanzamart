<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total',
        'name',
        'phone',
        'address',
        'city',
        'transaction_id',
        'payment_method',
        'payment_status',
        'delivery_confirmed',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class);
    }
}
