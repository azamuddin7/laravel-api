<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Shape;
use App\Http\Controllers\Api\ShapeController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShapeTest extends TestCase
{
    // get all shape test
    public function testIndexShape()
    {
        Shape::factory(10)->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/shapes');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'color',
                    'shape',
                    'timestamp',
                    'created_at',
                    'updated_at',
                ],
            ],
            "links" => [
                '*' => [
                    "url",
                    "label",
                    "active",
                ]
            ],
            "current_page",
            "first_page_url",
            "from",
            "last_page",
            "last_page_url",
            "next_page_url",
            "path",
            "per_page",
            "prev_page_url",
            "to",
            "total",
        ]);
    }
    /**
     * Test storing a new shape.
     */
    public function testStoreShape()
    {
        $user = User::factory()->create();

        // Create a mock data
        $mockData = [
            'name' => 'Test Shape',
            'color' => '#FFFFFF',
            'shape' => 'Circle',
            'timestamp' => now()->toDateString(),
        ];

        // Call the store method with the mock request
        $response = $this->actingAs($user)->postJson('/api/shapes', $mockData);

        // Assert that the response status code is 201 (created)
        $this->assertEquals(201, $response->getStatusCode());

        // Assert that the shape was created
        $this->assertDatabaseHas('shapes', ['name' => 'Test Shape']);
    }

    /**
     * Test retrieving a specific shape.
     */
    public function testShowShape()
    {
        $user = User::factory()->create();

        // Create a test shape
        $shape = Shape::factory()->create();

        // Call the show method with the shape ID
        $response = $this->actingAs($user)->getJson('/api/shapes/' . $shape->id);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Assert that the response data matches the shape data
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($shape->name, $responseData['data']['name']);
    }

    /**
     * Test updating an existing shape.
     */
    public function testUpdateShape()
    {
        $user = User::factory()->create();

        // Create a test shape
        $shape = Shape::factory()->create();

        // Create a mock request with updated data
        $mockUpdatedData = [
            'name' => 'Updated Shape',
        ];

        // Call the update method with the shape ID and mock data
        $response = $this->actingAs($user)->putJson('/api/shapes/' . $shape->id, $mockUpdatedData);

        // Assert that the response status code is 200 (OK)
        $this->assertEquals(200, $response->getStatusCode());

        // Assert that the shape was updated
        $this->assertDatabaseHas('shapes', ['id' => $shape->id, 'name' => 'Updated Shape']);
    }

    /**
     * Test deleting a shape.
     */
    public function testDeleteShape()
    {
        $user = User::factory()->create();

        // Create a test shape
        $shape = Shape::factory()->create();

        // Call the destroy method with the shape ID
        $response = $this->actingAs($user)->deleteJson('/api/shapes/' . $shape->id);

        // Assert that the response status code is 204 (no content)
        $this->assertEquals(204, $response->getStatusCode());

        $this->assertDatabaseMissing('shapes', [
            'id' => $shape->id,
        ]);
    }
}
