<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['branch_id', 'payment_method', 'total'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
