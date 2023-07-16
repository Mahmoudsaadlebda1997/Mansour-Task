<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'meal_id',
        'amount_to_pay',
    ];
    protected $table = 'order_details';
    // one to many relationship
    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }}
