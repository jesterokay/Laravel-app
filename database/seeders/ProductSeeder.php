<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please seed categories first.');
            return;
        }

        Product::factory()->make()->each(function ($product) use ($categories) {
            $product->category_id = $categories->first()->id;
            $product->save();
        });
    }
}