<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Itinerary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItineraryTest extends TestCase
{
    use RefreshDatabase; // Permet de réinitialiser la base de données après chaque test

    /**
     * Test pour vérifier si la création d'un itinéraire fonctionne correctement.
     */
    public function test_check_if_store_itinerary_is_correct(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson('/api/itineraries', [
            'title' => 'test unit',
            'category' => 'imensi',
            'duration' => 8,
            'image' => 'test.png',
            "destinations" => [
                [
                    "name" => "test",
                    "lodging" => "test",
                ],
                [
                    "name" => "test2",
                    "lodging" => "test2",
                ]
            ]
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('itineraries', [
            'title' => 'test unit',
            'category' => 'imensi',
            'duration' => 8,
            'image' => 'test.png',
        ]);

        $response->assertJson([
            'message' => 'Itinerary created successfully',
            'itinerary' => [
                'title' => 'test unit',
                'category' => 'imensi',
                'duration' => 8,
                'image' => 'test.png',
            ]
        ]);
    }

    /**
     * Test pour vérifier si la mise à jour d'un itinéraire fonctionne.
     */
    public function test_check_if_update_itinerary_is_correct(): void
    {
        $user = User::factory()->create();
        $itinerary = Itinerary::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->putJson("/api/itineraries/{$itinerary->id}", [
            'title' => 'update title 2',
            'category' => 'update categorie 2',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('itineraries', [
            'id' => $itinerary->id,
            'title' => 'update title 2',
            'category' => 'update categorie 2',
        ]);
    }

    /**
     * Test pour vérifier si la suppression d'un itinéraire fonctionne.
     */
    public function test_if_delete_itinerary_is_correct()
    {
        $user = User::factory()->create();
        $itinerary = Itinerary::factory()->create(['user_id' => $user->id]);

        // Authentifier l'utilisateur
        $this->actingAs($user);

        $response = $this->deleteJson("/api/itineraries/{$itinerary->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('itineraries', ['id' => $itinerary->id]);
    }
}
