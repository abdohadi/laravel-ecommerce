<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Category::class)->create(['name' => 'Laptops', 'slug' => 'laptops']);
        factory(Category::class)->create(['name' => 'Desktops', 'slug' => 'desktops']);
        factory(Category::class)->create(['name' => 'Cameras', 'slug' => 'cameras']);
        factory(Category::class)->create(['name' => 'Iphones', 'slug' => 'iphones']);
        factory(Category::class)->create(['name' => 'Tablets', 'slug' => 'tablets']);
    }
}
