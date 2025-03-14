<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestion des Itinéraires",
 *      description="Documentation pour l'API de gestion des itinéraires"
 * )
 */
class ItineraryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/itineraries",
     *     summary="Récupérer la liste des itinéraires",
     *     tags={"Itinéraires"},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filtrer par catégorie",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="min_duration",
     *         in="query",
     *         description="Filtrer par durée minimale",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="max_duration",
     *         in="query",
     *         description="Filtrer par durée maximale",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Liste des itinéraires récupérée avec succès"),
     *     @OA\Response(response=500, description="Erreur serveur")
     * )
     */
    public function index(Request $request)
    {
        $query = Itinerary::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('min_duration')) {
            $query->where('duration', '>=', $request->min_duration);
        }
        if ($request->has('max_duration')) {
            $query->where('duration', '<=', $request->max_duration);
        }
        $itineraries = $query->with('destinations')->get();
        return response()->json($itineraries);
    }

    /**
     * @OA\Post(
     *     path="/api/itineraries",
     *     summary="Créer un nouvel itinéraire",
     *     tags={"Itinéraires"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","category","duration","destinations"},
     *             @OA\Property(property="title", type="string", example="Voyage au Sahara"),
     *             @OA\Property(property="category", type="string", example="Désert"),
     *             @OA\Property(property="duration", type="integer", example=5),
     *             @OA\Property(
     *                 property="destinations",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="name", type="string", example="Merzouga"),
     *                     @OA\Property(property="lodging", type="string", example="Camp du désert"),
     *                     @OA\Property(property="places", type="array", @OA\Items(type="string"))
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Itinéraire créé avec succès"),
     *     @OA\Response(response=422, description="Données invalides"),
     *     @OA\Response(response=409, description="Itinéraire déjà existant")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'duration' => 'required|integer|min:1',
            'image' => 'nullable|string',
            'destinations' => 'required|array|min:2',
            'destinations.*.name' => 'required|string|max:255',
            'destinations.*.lodging' => 'nullable|string|max:255',
            'destinations.*.places' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $existingItinerary = Itinerary::where('title', $request->title)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingItinerary) {
            return response()->json(['message' => 'Cet itinéraire existe déjà.'], 409);
        }

        $itinerary = Itinerary::create([
            'title' => $request->title,
            'category' => $request->category,
            'duration' => $request->duration,
            'image' => $request->image,
            'user_id' => auth()->id(),
        ]);

        foreach ($request->destinations as $destinationData) {
            $destination = new Destination([
                'name' => $destinationData['name'],
                'lodging' => $destinationData['lodging'] ?? null,
                'places' => $destinationData['places'] ?? [],
            ]);
            $itinerary->destinations()->save($destination);
        }

        return response()->json([
            'message' => 'Itinerary created successfully',
            'itinerary' => $itinerary->load('destinations')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/itineraries/{id}",
     *     summary="Récupérer un itinéraire spécifique",
     *     tags={"Itinéraires"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'itinéraire",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Itinéraire récupéré"),
     *     @OA\Response(response=404, description="Itinéraire non trouvé")
     * )
     */
    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        return response()->json($itinerary);
    }
    /**
     * @OA\Put(
     *     path="/api/itineraries/{id}",
     *     summary="Mettre à jour un itinéraire",
     *     tags={"Itinéraires"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'itinéraire",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Voyage au Sahara Modifié"),
     *             @OA\Property(property="category", type="string", example="Aventure"),
     *             @OA\Property(property="duration", type="integer", example=7)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Itinéraire mis à jour"),
     *     @OA\Response(response=404, description="Itinéraire non trouvé")
     * )
     */
    public function update(Request $request, $id)
    {
        $itinerary = Itinerary::find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'duration' => 'nullable|integer|min:1',
            'image' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $itinerary->update([
            'title' => $request->title ?? $itinerary->title,
            'category' => $request->category ?? $itinerary->category,
            'duration' => $request->duration ?? $itinerary->duration,
            'image' => $request->image ?? $itinerary->image,
        ]);

        return response()->json($itinerary);
    }
    /**
     * @OA\Delete(
     *     path="/api/itineraries/{id}",
     *     summary="Supprimer un itinéraire",
     *     tags={"Itinéraires"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'itinéraire",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Itinéraire supprimé"),
     *     @OA\Response(response=404, description="Itinéraire non trouvé")
     * )
     */
    public function destroy($id)
    {
        $itinerary = Itinerary::find($id);
        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }
        $itinerary->destinations()->delete();
        $itinerary->delete();
        return response()->json(['message' => 'Itinéraire supprimé avec succès']);
    }
}
