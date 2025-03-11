<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class FavoriteItineraryController extends Controller
{

    public function addToWishlist(Request $request, $id)
    {
        $user = auth()->user();
        $itinerary = Itinerary::find($id);
        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }
        $user->wishlist()->attach($id);

        return response()->json(['message' => 'Itinéraire ajouté à la liste à visiter']);
    }

    public function removeFromWishlist(Request $request, $id)
    {
        $user = auth()->user();
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }
        $user->wishlist()->detach($id);

        return response()->json(['message' => 'Itinéraire retiré de la liste à visiter']);
    }
}
