<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Station;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Seat;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a user and authenticate
        $user = User::factory()->create(['password' => Hash::make('123456')]);

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
    }
}
