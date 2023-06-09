<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class kantorPusatLocation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'Iotanesia',
            'long' => '-6.235330316736099',
            'lat' => '106.8464199290355',
            'address'  => 'Jl. Tebet Barat Dalam Raya No.40, RT.14/RW.3, Tebet Bar., Kec. Tebet, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12810'
        ];

        if(!Location::where('name','Iotanesia')->first())Location::create($data);
    }
}
