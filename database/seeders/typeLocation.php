<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class typeLocation extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Location::where('name','Iotanesia')->first()->type != 'OFFICE') {
            Location::where('name','Iotanesia')->update(['type'=>'OFFICE']);
            Location::where('name','<>','Iotanesia')->update(['type'=>'HOME']);
        }
    }
}
