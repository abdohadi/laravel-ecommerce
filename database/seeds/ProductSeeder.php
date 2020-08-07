<?php

use App\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Product::class, 12)->create()->each(function($product) {
        	$product->update(['name' => 'Laptop ' . $product->id]);
        });
    }
}
