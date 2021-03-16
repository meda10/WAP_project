<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Helpers\AppHelper;
use App\Models\Discount;

class DiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $code = AppHelper::generateDiscountCode();
            Discount::create(['code' => $code, 'percent' => 30]);
        }

        for ($i = 0; $i < 20; $i++) {
            $code = AppHelper::generateDiscountCode();
            Discount::create(['code' => $code, 'percent' => 15]);
        }
    }
}
