<?php

use App\Product;
use App\Category;
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
        $laptopsCategory = Category::where('name', 'Laptops')->first();
        $desktopsCategory = Category::where('name', 'Desktops')->first();
        $camerasCategory = Category::where('name', 'Cameras')->first();
        $iphonesCategory = Category::where('name', 'Iphones')->first();
        $tabletsCategory = Category::where('name', 'Tablets')->first();

        $laptops = factory(Product::class, 10)->create();
        $i = 1;
        foreach ($laptops as $laptop) {
            $laptop->update([
                'name' => 'Laptop ' . $i,
            ]);
            $i++;

            $laptop->categories()->attach([$laptopsCategory->id, $desktopsCategory->id]);
        }

        $desktops = factory(Product::class, 10)->create();
        $i = 1;
        foreach ($desktops as $desktop) {
            $desktop->update([
                'name' => 'Desktop ' . $i,
            ]);
            $i++;

            $desktop->categories()->attach([$laptopsCategory->id, $desktopsCategory->id]);
        }

        $cameras = factory(Product::class, 10)->create();
        $i = 1;
        foreach ($cameras as $camera) {
            $camera->update([
                'name' => 'Camera ' . $i,
            ]);
            $i++;

            $camera->categories()->attach($camerasCategory);
        }

        $iphones = factory(Product::class, 10)->create();
        $i = 1;
        foreach ($iphones as $iphone) {
            $iphone->update([
                'name' => 'Iphone ' . $i,
            ]);
            $i++;

            $iphone->categories()->attach($iphonesCategory);
        }

        $tablets = factory(Product::class, 10)->create();
        $i = 1;
        foreach ($tablets as $tablet) {
            $tablet->update([
                'name' => 'Tablet ' . $i,
            ]);
            $i++;

            $tablet->categories()->attach($tabletsCategory);
        }
    }
}
