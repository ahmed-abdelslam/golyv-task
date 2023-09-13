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
}
