<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seat;

class TripsController extends Controller
{
    public function bookSeat(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'seat_id' => 'required|integer',
        ]);

        // Find the seat with the given ID
        $seat = Seat::findOrFail($request->seat_id);

        // Check if the seat is already booked
        if ($seat->is_booked) {
            return response()->json(['error' => 'Seat is already booked'], 400);
        }

        // Update the seat's status and user ID
        $seat->is_booked = true;
        $seat->user_id = auth()->id();
        $seat->save();

        // Return success message
        return response()->json(['message' => 'Seat booked successfully'], 200);
    }

    public function getAvailableSeats(Request $request)
    {
        // Validate the start and end station IDs
        $this->validate($request, [
            'start_station_id' => 'required|integer',
            'end_station_id' => 'required|integer',
        ]);

        // Extract the start and end station IDs from the request
        $startStationId = $request->start_station_id;
        $endStationId = $request->end_station_id;

        // Get the available seats based on the start and end station IDs
        $availableSeats = Seat::whereHas('bus.trips', function ($query) use ($startStationId, $endStationId) {
            $query->whereHas('stations', function ($query) use ($startStationId, $endStationId) {
                $query->where('station_id', $startStationId)
                    ->orWhere('station_id', $endStationId);
            });
        })->where('is_booked', false)->get('id');

        // Return the available seats as a JSON response
        return response()->json(['seats' => $availableSeats], 200);
    }
}
