<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
//    Table Status
//    1 = Avaliable
//    0 = Not Avaliable
    protected $fillable = [
        'capacity','status'
    ];
    protected $table = 'tables';
    // one to many relationship
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function reserve($customer, $fromTime, $toTime)
    {
        if ($this->status !== 'available') {
            // Table is not available, so create a reservation
            $reservation = Reservation::create([
                'customer_id' => $customer,
                'from_time' => $fromTime,
                'table_id' => $this->id,
                'to_time' => $toTime
            ]);
            return $reservation;
        } else {
            // Table is available, so set the status to 'reserved'
            $this->status = 'reserved';
            $this->save();
            return $this;
        }
    }
}
