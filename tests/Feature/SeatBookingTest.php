<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Bus;
use App\Models\Seat;

class SeatBookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_seat_booking_success(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a bus
        $bus = Bus::factory()->create();

        // Create a seat
        $seat = Seat::factory()->create(['bus_id' => $bus->id]);

        // Make a request to book the seat
        $response = $this->json('POST', '/api/trips/book-seat', [
            'seat_id' => $seat->id,
        ]);

        // Assert the response
        $response->assertStatus(200);
        $response->assertExactJson(['message' => 'Seat booked successfully']);

        // Assert that the seat is now booked
        $this->assertEquals($seat->fresh()->is_booked, 1);
        $this->assertEquals($user->id, $seat->fresh()->user_id);
    }

    public function test_seat_booking_failure(): void {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a bus
        $bus = Bus::factory()->create();

        // Create a seat
        $seat = Seat::factory()->create(['bus_id' => $bus->id, 'is_booked' => true]);

        // Make a request to book the seat
        $response = $this->json('POST', '/api/trips/book-seat', [
            'seat_id' => $seat->id,
        ]);

        // Assert the response
        $response->assertStatus(400);
        $response->assertExactJson(['error' => 'Seat is already booked']);

        // Assert that the seat is still booked
        $this->assertEquals($seat->fresh()->is_booked, 1);
    }
}
