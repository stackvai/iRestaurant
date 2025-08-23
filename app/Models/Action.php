<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['name'];

    public function roleAccess()
    {
        return $this->hasMany(AccessToRole::class);
    }

    public function userAccess()
    {
        return $this->hasMany(AccessToUser::class);
    }
}
