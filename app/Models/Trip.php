<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bus;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['departure_time', 'arrival_time', 'bus_id'];

    /**
     * Relationships
     */

    // bus relationship
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
