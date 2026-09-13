<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the localized name of the category according to current language (EN or SW).
     */
    public function getNameAttribute($value)
    {
        $locale = app()->getLocale();

        // 1. Check if an explicit translation key exists in messages.cat_{slug}
        if (!empty($this->slug)) {
            $key = 'messages.cat_' . str_replace('-', '_', $this->slug);
            if (Lang::has($key)) {
                return __($key);
            }
        }

        // 2. If value matches "Swahili Name (English Name)" format, parse according to locale
        if (preg_match('/^(.*?)\s*\((.*?)\)$/', $value, $matches)) {
            return $locale === 'en' ? trim($matches[2]) : trim($matches[1]);
        }

        return $value;
    }
}
