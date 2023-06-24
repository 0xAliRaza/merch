<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
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

        $imagesDirectory = storage_path('app/public/images');
        $smallDirectory = $imagesDirectory . '/small';
        $mediumDirectory = $imagesDirectory . '/medium';
        $largeDirectory = $imagesDirectory . '/large';
        $originalDirectory = $imagesDirectory;

        // Create directories if they don't exist
        File::makeDirectory($imagesDirectory, 0755, true, true);
        File::makeDirectory($smallDirectory, 0755, true, true);
        File::makeDirectory($mediumDirectory, 0755, true, true);
        File::makeDirectory($largeDirectory, 0755, true, true);
        File::makeDirectory($originalDirectory, 0755, true, true);

        $small = fake()->image($smallDirectory, 480, 480, null, false);
        $medium = fake()->image($mediumDirectory, 800, 800, null, false);
        $large = fake()->image($largeDirectory, 1200, 1200, null, false);
        $original = fake()->image($originalDirectory, 1920, 1080, null, false);

        return [
            'small' => 'small/' . $small,
            'medium' => 'medium/' . $medium,
            'large' => 'large/' . $large,
            'original' => $original,
        ];
    }
}
