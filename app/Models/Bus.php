<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trip;
use App\Models\Seat;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'plate_number'];

    /**
     * Relationships
     */

    // trips relationship
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // seats relationship
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
