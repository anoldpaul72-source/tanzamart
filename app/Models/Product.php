<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'vendor_id',
        'name',
        'price',
        'image',
        'details',
        'stock',
        'sales',
        'views',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/logo.jpg');
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        $parts = explode(',', $this->image);
        return asset('images/' . trim($parts[0]));
    }

    public function getImagesListAttribute()
    {
        if (!$this->image) {
            return [asset('images/logo.jpg')];
        }
        $raw = explode(',', $this->image);
        $urls = [];
        foreach ($raw as $img) {
            $img = trim($img);
            if (!empty($img)) {
                $urls[] = str_starts_with($img, 'http') ? $img : asset('images/' . $img);
            }
        }
        return count($urls) > 0 ? $urls : [asset('images/logo.jpg')];
    }
}
