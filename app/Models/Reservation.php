<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'table_id',
        'from_time',
        'to_time',
        'customer_id',
        'status'
    ];
    protected $table = 'reservations';
    // one to many relationship
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
