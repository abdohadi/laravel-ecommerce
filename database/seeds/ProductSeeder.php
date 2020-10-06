<?php

use App\Product;
use App\Category;
use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Data Type
        $dataType = $this->dataType('slug', 'products');
        if (!$dataType->exists) {
            $dataType->fill([
                'name'                  => 'products',
                'display_name_singular' => 'product',
                'display_name_plural'   => 'products',
                'icon'                  => 'voyager-bag',
                'model_name'            => 'App\\Product',
                'policy_name'           => '',
                'controller'            => 'App\\Http\\Controllers\\Voyager\\ProductsController',
                'generate_permissions'  => 1,
                'description'           => '',
            ])->save();
        }

        //Data Rows
        $postDataType = DataType::where('slug', 'products')->firstOrFail();
        $dataRow = $this->dataRow($postDataType, 'id');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'id',
                'required'     => 1,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 1,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'name');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'name',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'required|string',
                    ],
                ],
                'order'        => 2,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'details');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'text',
                'display_name' => 'details',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'required|string',
                    ],
                ],
                'order'        => 3,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'quantity');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'quantity',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'required|numeric',
                    ],
                ],
                'order'        => 4,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'price');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'number',
                'display_name' => 'price',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'required|numeric',
                    ],
                ],
                'order'        => 5,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'description');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'rich_text_box',
                'display_name' => 'description',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'string',
                    ],
                ],
                'order'        => 6,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'main_image');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'image',
                'display_name' => 'main_image',
                'required'     => 1,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'image',
                    ],
                ],
                'order' => 7,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'images');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'multiple_images',
                'display_name' => 'images',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    'validation' => [
                        'rule'  => 'image',
                    ],
                ],
                'order' => 8,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'featured');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'checkbox',
                'display_name' => 'featured',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 1,
                'add'          => 1,
                'delete'       => 1,
                'details'      => [
                    "on" => "Yes",
                    "off" => "No"
                ],
                'order'        => 9,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'created_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => 'created_at',
                'required'     => 0,
                'browse'       => 1,
                'read'         => 1,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 10,
            ])->save();
        }

        $dataRow = $this->dataRow($postDataType, 'updated_at');
        if (!$dataRow->exists) {
            $dataRow->fill([
                'type'         => 'timestamp',
                'display_name' => 'updated_at',
                'required'     => 0,
                'browse'       => 0,
                'read'         => 0,
                'edit'         => 0,
                'add'          => 0,
                'delete'       => 0,
                'order'        => 11,
            ])->save();
        }

        //Menu Item
        $menu = Menu::where('name', 'admin')->firstOrFail();
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => 'products',
            'url'     => '',
            'route'   => 'voyager.products.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'voyager-bag',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 3,
            ])->save();
        }

        //Permissions
        Permission::generateFor('products');


        // Create dummy products
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

    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew([
            'data_type_id' => $type->id,
            'field'        => $field,
        ]);
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
}
