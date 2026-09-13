<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'user', 'vendor', 'admin'
        'phone',
        'address',
        'city',
        'shop_name',
        'plan_type',
        'balance',
        'is_verified',
        'is_paid',
        'subscription_active',
        'subscription_end_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'is_verified' => 'boolean',
            'is_paid' => 'boolean',
            'subscription_active' => 'boolean',
            'subscription_end_date' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'user';
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'vendor_id');
    }

    public function disputes()
    {
        return $this->hasMany(Dispute::class, 'user_id');
    }
}
