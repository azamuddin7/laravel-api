<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Shape;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shape>
 */
class ShapeFactory extends Factory
{
    protected $model = Shape::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'color' => $this->faker->hexColor,
            'shape' => $this->faker->randomElement(['Circle', 'Square', 'Triangle']),
            'timestamp' => $this->faker->dateTime(),
        ];
    }
}
