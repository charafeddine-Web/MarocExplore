<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = Itinerary::with('destinations')->get();
        return response()->json($itineraries);
    }

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

        return response()->json($itinerary->load('destinations'), 201);
    }


    public function show($id)
    {
        $itinerary = Itinerary::with('destinations')->find($id);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinéraire non trouvé'], 404);
        }

        return response()->json($itinerary);
    }

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
