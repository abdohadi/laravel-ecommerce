<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'image' => 'macbook-pro.png',
        'details' => $faker->sentence,
        'quantity' => $faker->numberBetween(1, 20),
        'price' => $faker->randomFloat(2, 100, 20000),
        'description' => $faker->paragraph,
        'featured' => $faker->randomElement([TRUE, FALSE]),
    ];
});
