<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class dummyBanner extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ex = DB::table('banner_dashboard')->first();
        if(!$ex) {
            DB::table('banner_dashboard')->insert([
                [
                    'image' => 'public/images/banner/banner_1'
                ],
                [
                    'image' => 'public/images/banner/banner_2'
                ],
                [
                    'image' => 'public/images/banner/banner_3'
                ],
                [
                    'image' => 'public/images/banner/banner_4'
                ]
            ]);
        }
    }
}
