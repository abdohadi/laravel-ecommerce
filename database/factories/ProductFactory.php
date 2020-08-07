<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => md5(random_bytes(10)),
        'details' => $faker->sentence,
        'price' => $faker->randomFloat(2, 100, 20000),
        'description' => $faker->paragraph,
    ];
});
