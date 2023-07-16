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
    protected $fillable = [
        'capacity'
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
}
