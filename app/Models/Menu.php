<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'icon', 'href', 'sequance', 'status'];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function roleAccess()
    {
        return $this->hasMany(AccessToRole::class);
    }

    public function userAccess()
    {
        return $this->hasMany(AccessToUser::class);
    }
}
