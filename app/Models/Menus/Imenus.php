<?php
namespace App\Models\Menus;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Branch;

class Imenus extends Model
{
    use HasFactory;

    protected $table = 'imenus';

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
        'branch_id',   // ✅ add this
        'availability'
    ];

    protected $casts = [
        'availability' => 'array', // ✅ auto-cast JSON to array
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'imenus_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
