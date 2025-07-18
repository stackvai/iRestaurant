<?php
namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemOption extends Pivot
{
    protected $table = 'item_option';

    protected $fillable = ['item_id', 'option_id', 'is_required', 'max_selection'];
}
