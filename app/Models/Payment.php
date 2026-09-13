<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'transaction_id',
        'amount',
        'plan_name',
        'payment_method',
        'phone',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
