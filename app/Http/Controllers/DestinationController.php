<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DestinationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/destinations",
     *     summary="Liste de toutes les destinations",
     *     tags={"Destinations"},
     *     @OA\Response(response=200, description="Liste des destinations récupérée avec succès")
     * )
     */
    public function index()
    {
        $destinations = Destination::all();
        return response()->json($destinations, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/destinations",
     *     summary="Créer une nouvelle destination",
     *     tags={"Destinations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Paris"),
     *             @OA\Property(property="lodging", type="string", example="Hotel Paris"),
     *             @OA\Property(property="places", type="array", @OA\Items(type="string", example="Eiffel Tower"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Destination créée avec succès"),
     *     @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lodging' => 'nullable|string|max:255',
            'places' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $destination = Destination::create([
            'name' => $request->name,
            'lodging' => $request->lodging,
            'places' => $request->places ?? [],
        ]);

        return response()->json($destination, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/destinations/{id}",
     *     summary="Obtenir une destination par ID",
     *     tags={"Destinations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la destination",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Destination récupérée avec succès"),
     *     @OA\Response(response=404, description="Destination non trouvée")
     * )
     */
    public function show(string $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        return response()->json($destination, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/destinations/{id}",
     *     summary="Mettre à jour une destination",
     *     tags={"Destinations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la destination",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Paris"),
     *             @OA\Property(property="lodging", type="string", example="Hotel Paris"),
     *             @OA\Property(property="places", type="array", @OA\Items(type="string", example="Louvre Museum"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Destination mise à jour avec succès"),
     *     @OA\Response(response=422, description="Validation échouée"),
     *     @OA\Response(response=404, description="Destination non trouvée")
     * )
     */
    public function update(Request $request, string $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'lodging' => 'nullable|string|max:255',
            'places' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $destination->update($request->only(['name', 'lodging', 'places']));

        return response()->json($destination, 200);
    }


    /**
     * @OA\Delete(
     *     path="/api/destinations/{id}",
     *     summary="Supprimer une destination",
     *     tags={"Destinations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la destination",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Destination supprimée avec succès"),
     *     @OA\Response(response=404, description="Destination non trouvée")
     * )
     */
    public function destroy(string $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => 'Destination not found'], 404);
        }

        $destination->delete();

        return response()->json(['message' => 'Destination deleted successfully'], 200);
    }
}
