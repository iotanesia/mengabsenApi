<?php

namespace Database\Seeders;

use App\Models\GlobalParam;
use Illuminate\Database\Seeder;

class version extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!GlobalParam::where('param_type','Version')->first()) {
            GlobalParam::insert([
                [
                    'param_type'=>'Version',
                    'param_code'=>'IOS',
                    'param_name'=>'1.0.0',
                ],
                [
                    'param_type'=>'Version',
                    'param_code'=>'ANDROID',
                    'param_name'=>'1.0.0',
                ]
            ]);
        }
    }
}
