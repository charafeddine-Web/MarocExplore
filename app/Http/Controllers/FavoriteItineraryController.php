<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class FavoriteItineraryController extends Controller
{
    public function addToWishlist($id)
    {
        $user = auth()->user();
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        if ($user->wishlist()->where('itinerary_id', $id)->exists()) {
            return response()->json(['message' => 'Itinéraire déjà dans votre liste à visiter'], 409);
        }
        $user->wishlist()->attach($id);

        return response()->json(['message' => 'Itinéraire ajouté à la liste à visiter']);
    }

    public function index()
    {
        $user = auth()->user();
        $favorites = $user->wishlist()->with('user')->get();

        return response()->json($favorites);
    }

    public function removeFromWishlist($id)
    {
        $user = auth()->user();
        $itinerary = Itinerary::find($id);
        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        if (!$user->wishlist()->where('itinerary_id', $id)->exists()) {
            return response()->json(['message' => 'Itinéraire non trouvé dans votre liste'], 404);
        }

        $user->wishlist()->detach($id);

        return response()->json(['message' => 'Itinéraire retiré de la liste à visiter']);
    }
}
