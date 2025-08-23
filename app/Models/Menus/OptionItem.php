<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionItem extends Model
{
    use HasFactory;

    protected $fillable = ['option_id', 'name', 'description', 'extra_price','position','tax','image', 'is_default', 'is_active'];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
