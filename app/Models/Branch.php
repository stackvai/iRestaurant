<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'phone',
        'image',
        'status',
    ];
    public function menus()
    {
        return $this->hasMany(\App\Models\Menus\Imenus::class, 'branch_id');
    }
}
