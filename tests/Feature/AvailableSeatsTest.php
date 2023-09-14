<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Station;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Seat;

class AvailableSeatsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_get_available_seats_returns_available_seats(): void
    {
        // Create test data
        $cairoStation = Station::factory()->create(['name' => 'Cairo']);
        $alFayyumStation = Station::factory()->create(['name' => 'AlFayyum']);
        $alMinyaStation = Station::factory()->create(['name' => 'AlMinya']);
        $asyutStation = Station::factory()->create(['name' => 'Asyut']);

        // the bus of cairo asyut trip
        $busCairoAsyut = Bus::factory()->create();

        // for simplicty we will create only 3 seats instead of 12
        $seatOneOfBusCairoAsyut = Seat::factory()->create(['bus_id' => $busCairoAsyut->id, 'is_booked' => false]);
        $seatTwoOfBusCairoAsyut = Seat::factory()->create(['bus_id' => $busCairoAsyut->id, 'is_booked' => true]);
        $seatThreeOfBusCairoAsyut = Seat::factory()->create(['bus_id' => $busCairoAsyut->id, 'is_booked' => false]);

        // trip from cairo to asyut
        $cairoAsyutTrip = Trip::factory()->create(['bus_id' => $busCairoAsyut]);
        // connect the trip with the stations
        $cairoAsyutTrip->stations()->attach([$cairoStation->id, $alFayyumStation->id, $alMinyaStation->id, $asyutStation->id]);


        // the bus of cairo asyut trip
        $busAlFayyumAsyut = Bus::factory()->create();

        // for simplicty we will create only 3 seats instead of 12
        $seatOneOfBusAlFayyumAsyut = Seat::factory()->create(['bus_id' => $busAlFayyumAsyut->id, 'is_booked' => true]);
        $seatTwoOfBusAlFayyumAsyut = Seat::factory()->create(['bus_id' => $busAlFayyumAsyut->id, 'is_booked' => true]);
        $seatThreeOfBusAlFayyumAsyut = Seat::factory()->create(['bus_id' => $busAlFayyumAsyut->id, 'is_booked' => false]);

        // trip from alFayyum to asyut
        $alFayyumAsyutTrip = Trip::factory()->create(['bus_id' => $busAlFayyumAsyut]);
        // connect the trip with the stations
        $alFayyumAsyutTrip->stations()->attach([$alFayyumStation->id, $alMinyaStation->id, $asyutStation->id]);


        // Make a request to get available seats
        $response = $this->json('GET', '/api/trips/available-seats', [
            'start_station_id' => $cairoStation->id,
            'end_station_id' => $asyutStation->id,
        ]);

        // Assert response status code
        $response->assertStatus(200);

        // Assert the response contains the available seats
        // User can book seat 1 of cairo asyut bus or seat 3 of cairo asyut bus or seat 3 of alfayyum asyut bus
        $response->assertExactJson([
            'seats' => [
                ['id' => $seatOneOfBusCairoAsyut->id],
                ['id' => $seatThreeOfBusCairoAsyut->id],
                ['id' => $seatThreeOfBusAlFayyumAsyut->id]
            ]
        ]);

        // Assert the response does not contain the booked seats
        $response->assertJsonMissing([
            'seats' => [
                ['id' => $seatTwoOfBusCairoAsyut->id],
                ['id' => $seatOneOfBusAlFayyumAsyut->id],
                ['id' => $seatTwoOfBusAlFayyumAsyut->id]
            ]
        ]);
    }
}
