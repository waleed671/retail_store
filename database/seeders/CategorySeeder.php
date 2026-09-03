<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Mobiles & Electronics' => 'Smartphones, laptops, and accessories.',
            'Fashion & Apparel' => 'Traditional and western clothing for men, women, and kids.',
            'Beauty & Personal Care' => 'Cosmetics, skincare, and grooming products.',
            'Home & Kitchen' => 'Appliances, furniture, and home décor.',
            'Health & Wellness' => 'Supplements, fitness gear, and health devices.',
            'Groceries' => 'Packaged foods and everyday essentials.',
        ];

        $i = 0;
        foreach ($categories as $name => $description) {
            Category::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_active' => true,
                    'sort_order' => $i++,
                ]
            );
        }
    }
}
