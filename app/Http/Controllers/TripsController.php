<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seat;

class TripsController extends Controller
{
    public function bookSeat(Request $request)
    {
        $this->validate($request, [
            'seat_id' => 'required|integer',
        ]);

        $seat = Seat::findOrFail($request->seat_id);

        if ($seat->is_booked) {
            return response()->json(['error' => 'Seat is already booked'], 400);
        }

        $seat->is_booked = true;
        $seat->user_id = auth()->id();
        $seat->save();

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
