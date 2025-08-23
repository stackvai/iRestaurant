<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemOption extends Pivot
{
    protected $table = 'item_option';

    protected $fillable = [
        'item_id',
        'option_id',
        'is_required',
        'min_selection',
        'max_selection',
        'multiple_selection'
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    public function optionItems()
    {
        return $this->hasMany(OptionItem::class, 'option_id', 'option_id');
    }
}
