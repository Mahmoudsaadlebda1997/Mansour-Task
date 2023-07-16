<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'price',
        'description',
        'quantity_available',
        'discount'
    ];
    protected $table = 'meals';
    // one to many relationship
    public function orderDetails()
    {
        return $this->hasMany(Order::class);
    }
}
