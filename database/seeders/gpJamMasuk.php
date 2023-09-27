<?php

namespace Database\Seeders;

use App\Models\GlobalParam;
use Illuminate\Database\Seeder;

class gpJamMasuk extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GlobalParam::firstOrCreate([
            'param_type'=>'ABSENSI',
            'param_code'=>'JAM_MASUK',
            'param_name'=>'09:00'
        ]);
    }
}
