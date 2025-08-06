<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'sku' => $this->faker->unique()->numerify('SKU####'),
            'barcode' => $this->faker->unique()->numerify('BAR####'),
            'qrcode' => $this->faker->unique()->numerify('QR####'),
            'expedition_date' => $this->faker->date(),
            'expiration_date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'category_id' => Category::all()->random()->id,
        ];
    }
}
