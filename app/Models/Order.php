<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'table_id',
        'reservation_id',
        'customer_id',
        'total_paid',
        'date',
        'waiter_id'
    ];
    protected $table = 'orders';
    // one to many relationship
    public function waiter()
    {
        return $this->belongsTo(Waiter::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
