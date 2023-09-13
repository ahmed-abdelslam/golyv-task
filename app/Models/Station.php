<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trip;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Relationships
     */

    // trips relationship
    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }
}
