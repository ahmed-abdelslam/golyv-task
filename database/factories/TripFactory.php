<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Trip;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class TripFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Trip::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departureTime = fake()->time('H:i');
        $arrivalTime = Carbon::parse($departureTime)->addHours(fake()->numberBetween(1, 10))->format('H:i');

        return [
            'departure_time' => $departureTime,
            'arrival_time' => $arrivalTime,
        ];
    }
}
