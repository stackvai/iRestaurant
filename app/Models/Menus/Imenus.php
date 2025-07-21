<?php

namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Imenus extends Model
{
    use HasFactory;
    protected $table = 'imenus';
    protected $fillable = ['name', 'description', 'image', 'is_active'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'imenus_id');
    }
}
