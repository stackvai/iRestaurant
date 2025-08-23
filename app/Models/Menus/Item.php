<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'description', 'price','offer_price','tax', 'serial_no', 'image', 'is_active'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'item_option')
            ->withPivot(['is_required', 'min_selection', 'max_selection', 'multiple_selection'])
            ->withTimestamps();
    }
    public function itemOptions()
    {
        return $this->hasMany(ItemOption::class);
    }
}
