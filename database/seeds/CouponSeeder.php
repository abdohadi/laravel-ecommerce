<?php

use App\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coupon::create([
        	'code' => 'ABC123',
        	'type' => Coupon::FIXED_VALUE,
        	'fixed_value' => 20
        ]);

        Coupon::create([
        	'code' => 'DEF456',
        	'type' => Coupon::PERCENT_OFF,
        	'percent_off' => 10
        ]);
    }
}
