<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_active'];

    public function optionItems()
    {
        return $this->hasMany(OptionItem::class, 'option_id');
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_option')
            ->withPivot(['is_required', 'min_selection', 'max_selection', 'multiple_selection'])
            ->withTimestamps();
    }
}
