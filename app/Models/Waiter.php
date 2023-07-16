<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiter extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone'
    ];
    protected $table = 'waiters';
    // one to many relationship
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
