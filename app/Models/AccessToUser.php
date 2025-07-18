<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessToUser extends Model
{
    protected $table = 'access_to_user';
    protected $fillable = ['user_id', 'role_id', 'menu_id', 'granted'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }
}
