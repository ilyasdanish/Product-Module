<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Products\Entities\Product;
use Modules\Products\Entities\User;

class ProductsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::first();

        Product::create([
            'name' => 'Product 1',
            'price' => 20.00,
            'status' => 'active',
            'type' => 'item',
            'added_by' => $user->id,
        ]);
    }
}
