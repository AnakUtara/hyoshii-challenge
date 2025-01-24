<?php

namespace Database\Factories;

use App\Models\PersonInCharge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pack>
 */
class PackingPerformanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'person_in_charge_id' => PersonInCharge::all()->random()->id,
            'admin_id' => 1,
            'timestamp' => today()->subDays(2),
            'gross_weight_kg' => fake()->randomFloat(1, 26.4, 41),
            'qty_pack_a_0_2kg' => fake()->numberBetween(22, 50),
            'qty_pack_b_0_3kg' => fake()->numberBetween(22, 50),
            'qty_pack_c_0_4kg' => fake()->numberBetween(22, 50),
            'reject_kg' => fake()->randomFloat(1, 0, 3),
        ];
    }

    public function pic1(): static {
        return $this->state(fn (array $attributes) => [
            'person_in_charge_id' => 1
        ]);
    }

    public function pic2(): static {
        return $this->state(fn (array $attributes) => [
            'person_in_charge_id' => 2
        ]);
    }

    public function pic3(): static {
        return $this->state(fn (array $attributes) => [
            'person_in_charge_id' => 3
        ]);
    }
}
