<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Itinerary extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'category', 'duration', 'image', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'FavoriteItinerary', 'itinerary_id', 'user_id');
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }


}
