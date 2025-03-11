<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteItinerary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'itinerary_id',
    ];

    /**
     * Relation avec User (Un favori appartient à un utilisateur)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec Itinerary (Un favori appartient à un itinéraire)
     */
    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }
}
