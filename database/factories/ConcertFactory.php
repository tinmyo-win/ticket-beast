<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concert>
 */
class ConcertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'The Example Band',
            'subtitle' => 'with Fake Band',
            'date' => Carbon::now()->addWeek()->format('Y-m-d H:i:s'),
            'ticket_price' => 100000,
            'venue' => 'Example Hotel',
            'venue_address' => '1234 Street',
            'city' => 'Fake City',
            'state' => 'FC',
            'zip'  => '10220',
            'published_at' => null,
            'additional_information' => 'For example, additional information',
            'user_id' => User::factory(),
            'ticket_quantity' => 10,
            'poster_image_path' => null,
        ];
    }

    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => now()->subDay(),
            ];
        });
    }

    public function unpublished(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => null,
            ];
        });
    }
}
