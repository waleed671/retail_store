<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['Mobiles & Electronics', 'Wireless Bluetooth Earbuds', 3499, 2999, 40],
            ['Mobiles & Electronics', 'Fast Charging Power Bank 20000mAh', 4200, null, 25],
            ['Mobiles & Electronics', 'USB-C Charging Cable (1m)', 450, null, 100],
            ['Fashion & Apparel', 'Men\'s Stitched Shalwar Kameez', 2800, 2400, 30],
            ['Fashion & Apparel', 'Women\'s Lawn Suit (3-Piece)', 3500, 2999, 20],
            ['Fashion & Apparel', 'Kids Casual T-Shirt', 900, null, 60],
            ['Beauty & Personal Care', 'Herbal Face Wash 100ml', 650, null, 50],
            ['Beauty & Personal Care', 'Whitening Cream 50g', 780, 650, 35],
            ['Home & Kitchen', 'Non-Stick Cooking Pan Set', 4500, 3999, 15],
            ['Home & Kitchen', 'Electric Kettle 1.7L', 2600, null, 22],
            ['Health & Wellness', 'Multivitamin Tablets (60 count)', 1500, 1299, 45],
            ['Groceries', 'Basmati Rice 5kg', 1800, null, 80],
        ];

        foreach ($products as [$categoryName, $name, $price, $discount, $stock]) {
            $category = Category::where('name', $categoryName)->first();

            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $category->id,
                    'name' => $name,
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'description' => "High quality {$name} sourced for our local customers. Cash on delivery available across major cities.",
                    'specifications' => null,
                    'price' => $price,
                    'discount_price' => $discount,
                    'stock' => $stock,
                    'is_featured' => $discount !== null,
                    'is_active' => true,
                ]
            );
        }
    }
}
