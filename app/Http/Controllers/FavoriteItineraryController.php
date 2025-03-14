<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;

class FavoriteItineraryController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/wishlist/{id}",
     *     summary="Ajouter un itinéraire à la liste à visiter",
     *     tags={"Favoris"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'itinéraire",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Itinéraire ajouté à la liste à visiter"),
     *     @OA\Response(response=404, description="Itinéraire non trouvé"),
     *     @OA\Response(response=409, description="Itinéraire déjà dans la liste")
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/api/wishlist",
     *     summary="Récupérer les itinéraires dans la liste à visiter",
     *     tags={"Favoris"},
     *     @OA\Response(response=200, description="Liste des itinéraires récupérée avec succès")
     * )
     */
    public function index()
    {
        $user = auth()->user();
        $favorites = $user->wishlist()->with('user')->get();

        return response()->json($favorites);
    }
    /**
     * @OA\Delete(
     *     path="/api/wishlist/{id}",
     *     summary="Supprimer un itinéraire de la liste à visiter",
     *     tags={"Favoris"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'itinéraire",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Itinéraire retiré de la liste à visiter"),
     *     @OA\Response(response=404, description="Itinéraire non trouvé")
     * )
     */
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
