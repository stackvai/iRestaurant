<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['imenus_id', 'name', 'description', 'image', 'is_active'];

    public function menu()
    {
        return $this->belongsTo(Imenus::class, 'imenus_id');
    }

public function items()
{
    return $this->hasMany(Item::class)->orderBy('serial_no', 'asc');
}

}
