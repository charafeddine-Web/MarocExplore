<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'lodging', 'itinerary_id'];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

}
