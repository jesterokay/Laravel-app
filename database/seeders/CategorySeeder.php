<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Gadgets and devices'],
            ['name' => 'Books', 'description' => 'Printed and digital books'],
            ['name' => 'Clothing', 'description' => 'Apparel and accessories'],
            ['name' => 'Home & Kitchen', 'description' => 'Furniture, appliances, and decor'],
            ['name' => 'Sports & Outdoors', 'description' => 'Equipment and gear for an active lifestyle'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}