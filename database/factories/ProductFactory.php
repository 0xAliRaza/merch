<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'name' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'discount' => fake()->numberBetween(0, 50),
            'price' => fake()->randomFloat(2, 10, 1000)
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $images = ProductImage::factory(3)->create(['product_id' => $product->id]);
            $defaultImage = $images->random();
            $product->default_image = $defaultImage->id;
            $product->save();
        });
    }
}

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductImageFactory extends Factory
{
    public function definition()
    {
        $small = fake()->image(storage_path('app/public/images') . '/small', 480, 480, null, false);
        $medium = fake()->image(storage_path('app/public/images') . '/medium', 800, 800, null, false);
        $large = fake()->image(storage_path('app/public/images') . '/large', 1200, 1200, null, false);
        $original = fake()->image(storage_path('app/public/images'), 1920, 1080, null, false);

        return [
            'small' => $small,
            'medium' => $medium,
            'large' => $large,
            'original' => $original,
        ];
    }
}
